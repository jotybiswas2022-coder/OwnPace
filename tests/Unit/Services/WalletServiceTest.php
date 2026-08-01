<?php

namespace Tests\Unit\Services;

use App\Models\BankAccount;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WalletWithdrawalRequest;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Money-sensitive: refund-then-withdraw is the exact flow the product spec
 * calls out. Getting the 10% fee math wrong here means real money errors.
 */
class WalletServiceTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        return User::factory()->create();
    }

    private function bankFor(User $user): BankAccount
    {
        return BankAccount::create([
            'user_id' => $user->id,
            'bank_name' => 'GTBank',
            'account_number' => '0123456789',
            'account_name' => $user->name,
        ]);
    }

    private function orderWithPaidAmount(User $user, float $paid): Order
    {
        return Order::create([
            'order_number' => 'ORD-REF-' . uniqid(),
            'user_id' => $user->id,
            'status' => 'processing',
            'total_amount' => $paid,
            'base_amount' => $paid,
            'grand_total' => $paid,
            'paid_amount' => $paid,
            'remaining_amount' => 0,
            'payment_type' => 'full',
            'delivery_status' => 'processing',
        ]);
    }

    // ===== REFUND =====

    public function test_cancellation_refund_is_100_percent_and_withdrawable(): void
    {
        $user = $this->user();
        $order = $this->orderWithPaidAmount($user, 1000);

        $txn = WalletService::refundForCancellation($order);

        $this->assertEquals(1000.0, (float) $txn->amount);
        // The 10%-fee withdrawal rule is defined for cancellation refunds.
        $this->assertTrue($txn->withdrawable, 'Cancellation refunds are withdrawable (10% fee applies)');
        $this->assertEquals('refund', $txn->type);
        $this->assertEquals(1000.0, (float) $user->wallet->balance);
        $this->assertEquals(1000.0, WalletService::withdrawableBalance($user->wallet));
    }

    public function test_refund_for_order_with_nothing_paid_returns_null(): void
    {
        $user = $this->user();
        $order = $this->orderWithPaidAmount($user, 0);

        $this->assertNull(WalletService::refundForCancellation($order));
        $this->assertEquals(0.0, (float) $user->wallet?->balance ?? 0);
    }

    // ===== WITHDRAWABLE POOL =====

    public function test_withdrawable_balance_tracks_withdrawable_credits_only(): void
    {
        $user = $this->user();
        $wallet = WalletService::walletFor($user);

        WalletService::credit($wallet, 5000, 'cashback', 'Admin cashback', true);
        WalletService::credit($wallet, 2000, 'store_credit', 'Admin store credit', false);

        $this->assertEquals(5000.0, WalletService::withdrawableBalance($wallet));
        $this->assertEquals(7000.0, (float) $wallet->balance);
    }

    public function test_withdrawal_reduces_balance_and_withdrawable_pool(): void
    {
        $user = $this->user();
        $wallet = WalletService::walletFor($user);
        WalletService::credit($wallet, 10000, 'cashback', 'Admin cashback', true);

        $request = WalletService::requestWithdrawal($user, 10000, $this->bankFor($user));

        $this->assertEquals('pending', $request->status);
        // 10% of 10000 = 1000 fee; net 9000 to the bank.
        $this->assertEquals(1000.0, (float) $request->fee);
        $this->assertEquals(9000.0, (float) $request->net_amount);
        // Funds held immediately — gone from balance AND the pool.
        $this->assertEquals(0.0, (float) $wallet->fresh()->balance);
        $this->assertEquals(0.0, WalletService::withdrawableBalance($wallet->fresh()));
    }

    // ===== 10% FEE MATH =====

    public function test_withdrawal_fee_is_flat_10_percent(): void
    {
        $this->assertEquals(10.0, WalletService::withdrawalFeePercent());
        $this->assertEquals(100.0, WalletService::withdrawalFee(1000));
        $this->assertEquals(900.0, WalletService::netWithdrawal(1000));
    }

    public function test_fee_math_on_odd_amounts_rounds_to_kobo(): void
    {
        // 1250 × 10% = 125 exactly; net 1125.
        $this->assertEquals(125.0, WalletService::withdrawalFee(1250));
        $this->assertEquals(1125.0, WalletService::netWithdrawal(1250));

        // 333.33 × 10% = 33.333 → rounds to 33.33; net 300.00.
        $this->assertEquals(33.33, WalletService::withdrawalFee(333.33));
        $this->assertEquals(300.0, WalletService::netWithdrawal(333.33));
    }

    public function test_fee_percent_reads_from_settings(): void
    {
        Setting::create(['store_name' => 'OwnPace', 'withdrawal_fee_percent' => 5]);

        $this->assertEquals(5.0, WalletService::withdrawalFeePercent());
        $this->assertEquals(50.0, WalletService::withdrawalFee(1000));
        $this->assertEquals(950.0, WalletService::netWithdrawal(1000));
    }

    public function test_request_withdrawal_rejects_more_than_withdrawable_pool(): void
    {
        $user = $this->user();
        $wallet = WalletService::walletFor($user);
        WalletService::credit($wallet, 1000, 'store_credit', 'Store credit', false); // not withdrawable

        $this->expectException(\InvalidArgumentException::class);

        WalletService::requestWithdrawal($user, 1000, $this->bankFor($user));
    }

    public function test_request_withdrawal_rejects_below_minimum(): void
    {
        $user = $this->user();
        $wallet = WalletService::walletFor($user);
        WalletService::credit($wallet, 5000, 'cashback', 'Admin cashback', true);

        $this->expectException(\InvalidArgumentException::class);

        WalletService::requestWithdrawal($user, 50, $this->bankFor($user));
    }

    // ===== FULL REFUND-THEN-WITHDRAW FLOW =====

    public function test_refund_then_withdraw_flow_exact_money(): void
    {
        $user = $this->user();
        $order = $this->orderWithPaidAmount($user, 2500);

        // 1) Cancellation refunds 100% of paid, withdrawable.
        WalletService::refundForCancellation($order);
        $this->assertEquals(2500.0, (float) $user->wallet->balance);
        $this->assertEquals(2500.0, WalletService::withdrawableBalance($user->wallet));

        // 2) Customer withdraws the full 2500 → 10% fee, 90% to bank.
        $bank = $this->bankFor($user);
        $request = WalletService::requestWithdrawal($user, 2500, $bank);

        $this->assertEquals(250.0, (float) $request->fee);
        $this->assertEquals(2250.0, (float) $request->net_amount);
        $this->assertEquals('pending', $request->status);

        // 3) Held: 2500 gone from balance and from the withdrawable pool.
        $this->assertEquals(0.0, (float) $user->wallet->fresh()->balance);
        $this->assertEquals(0.0, WalletService::withdrawableBalance($user->wallet->fresh()));

        // 4) Admin completes it — money stays out, nothing is credited back.
        WalletService::completeWithdrawal($request);
        $this->assertEquals('completed', $request->fresh()->status);
        $this->assertEquals(0.0, (float) $user->wallet->fresh()->balance);
    }

    public function test_failed_withdrawal_returns_held_funds_to_withdrawable_pool(): void
    {
        $user = $this->user();
        $wallet = WalletService::walletFor($user);
        WalletService::credit($wallet, 10000, 'cashback', 'Admin cashback', true);

        $request = WalletService::requestWithdrawal($user, 10000, $this->bankFor($user));
        $this->assertEquals(0.0, (float) $wallet->fresh()->balance);

        WalletService::failWithdrawal($request, 'Bank details mismatch');

        $this->assertEquals('failed', $request->fresh()->status);
        // The full 10000 returns, and it is withdrawable again (it came from the pool).
        $this->assertEquals(10000.0, (float) $wallet->fresh()->balance);
        $this->assertEquals(10000.0, WalletService::withdrawableBalance($wallet->fresh()));
    }

    // ===== TOP-UPS =====

    public function test_topup_is_not_withdrawable_by_default(): void
    {
        $user = $this->user();
        $wallet = WalletService::walletFor($user);

        WalletService::credit($wallet, 5000, 'deposit', 'Wallet funding', WalletService::topUpWithdrawalAllowed());

        $this->assertEquals(5000.0, (float) $wallet->balance);
        $this->assertEquals(0.0, WalletService::withdrawableBalance($wallet));
    }

    public function test_topup_becomes_withdrawable_when_admin_allows(): void
    {
        Setting::create(['store_name' => 'OwnPace', 'allow_topup_withdrawal' => true]);
        $user = $this->user();
        $wallet = WalletService::walletFor($user);

        WalletService::credit($wallet, 5000, 'deposit', 'Wallet funding', WalletService::topUpWithdrawalAllowed());

        $this->assertEquals(5000.0, WalletService::withdrawableBalance($wallet));
    }

    // ===== BONUS =====

    public function test_topup_bonus_is_store_credit_never_withdrawable(): void
    {
        Setting::create(['store_name' => 'OwnPace', 'topup_bonus_percent' => 5]);
        $user = $this->user();
        $wallet = WalletService::walletFor($user);

        WalletService::credit($wallet, 10000, 'deposit', 'Wallet funding', false);
        $bonus = WalletService::topUpBonus(10000);
        WalletService::credit($wallet, $bonus, 'bonus', 'Top-up bonus (5%)', false);

        $this->assertEquals(500.0, $bonus);
        $this->assertEquals(10500.0, (float) $wallet->balance);
        $this->assertEquals(0.0, WalletService::withdrawableBalance($wallet));
    }

    // ===== LEDGER RECONCILIATION =====

    public function test_spending_wallet_money_shrinks_withdrawable_pool(): void
    {
        $user = $this->user();
        $wallet = WalletService::walletFor($user);

        WalletService::credit($wallet, 1000, 'cashback', 'Cashback', true);
        WalletService::debit($wallet, 400, 'payment', 'Order payment');

        // Spent 400 — the withdrawable pool is clamped to the remaining 600.
        $this->assertEquals(600.0, (float) $wallet->balance);
        $this->assertEquals(600.0, WalletService::withdrawableBalance($wallet));

        // Cannot withdraw more than the (clamped) pool.
        $this->expectException(\InvalidArgumentException::class);
        WalletService::requestWithdrawal($user, 700, $this->bankFor($user));
    }

    public function test_ledger_reconciles_after_mixed_activity(): void
    {
        $user = $this->user();
        $wallet = WalletService::walletFor($user);

        WalletService::credit($wallet, 1000, 'deposit', 'Funding', false);
        WalletService::credit($wallet, 500, 'cashback', 'Cashback', true);
        WalletService::debit($wallet, 300, 'payment', 'Order payment');

        $this->assertEquals(1200.0, (float) $wallet->balance);
        // 500 withdrawable (cashback), clamped to the 1200 balance.
        $this->assertEquals(500.0, WalletService::withdrawableBalance($wallet));

        // Every ledger row must carry balance_before/after that chains exactly.
        $rows = $wallet->transactions()->orderBy('id')->get();
        $running = 0.0;
        foreach ($rows as $row) {
            $this->assertEquals($running, (float) $row->balance_before);
            $running = (float) $row->balance_after;
        }
        $this->assertEquals((float) $wallet->balance, $running);
    }
}
