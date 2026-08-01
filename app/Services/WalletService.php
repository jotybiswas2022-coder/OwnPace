<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WalletWithdrawalRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * WalletService — the single source of truth for every wallet money decision.
 *
 * Every credit/debit updates the balance AND writes a WalletTransaction with
 * balance_before/balance_after, so the ledger always reconciles. The
 * withdrawable flag is stored per transaction; withdrawableBalance() derives
 * the withdrawable pool from the ledger, never from a cached column.
 *
 * Key rules (money-sensitive — see tests/Unit/Services/WalletServiceTest.php):
 *  - Cancellation refunds: 100% of what the customer paid, withdrawable = true
 *    (the 10%-fee withdrawal rule is defined for these funds — see the
 *    refund-then-withdraw flow test). To make refunds spend-only instead, flip
 *    the single flag in refundForCancellation().
 *  - Withdrawal: flat withdrawal_fee_percent (default 10%) is deducted, the
 *    net 90% goes to the linked bank. Requests are logged with a status for
 *    admin review. Funds are only ever withdrawn from the withdrawable pool.
 *  - Top-ups: withdrawable only if the admin setting allow_topup_withdrawal is
 *    on (client confirmed: default OFF — spend-only).
 *  - Withdrawable balance is ALWAYS clamped to the actual balance: spending
 *    money reduces what can be withdrawn.
 */
class WalletService
{
    /**
     * Get (or create) the wallet for a user.
     *
     * Uses firstOrCreate (not a bare create) because accessing $user->wallet
     * caches a null relation — a second walletFor() in the same request would
     * otherwise try to create a duplicate and hit the unique constraint.
     */
    public static function walletFor(User $user): Wallet
    {
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        $user->setRelation('wallet', $wallet);

        return $wallet;
    }

    /**
     * The wallet's withdrawable pool: credits marked withdrawable minus
     * withdrawals, clamped to the actual balance. The clamp matters: spending
     * money from the wallet reduces what can be withdrawn — otherwise a
     * customer who spent their withdrawable cash could still try to withdraw
     * an amount the wallet no longer holds.
     */
    public static function withdrawableBalance(Wallet $wallet): float
    {
        $credited = (float) $wallet->transactions()
            ->where('withdrawable', true)
            ->where('type', '!=', 'withdrawal')
            ->sum('amount');

        $withdrawn = (float) $wallet->transactions()
            ->where('type', 'withdrawal')
            ->sum('amount');

        $pool = max(0, $credited - $withdrawn);

        return MoneyService::round(min($pool, (float) $wallet->balance));
    }

    /**
     * Credit a wallet: bump balance, log the ledger row.
     */
    public static function credit(
        Wallet $wallet,
        float $amount,
        string $type,
        string $description,
        bool $withdrawable,
        ?string $reference = null
    ): WalletTransaction {
        $amount = MoneyService::round($amount);
        $before = (float) $wallet->balance;

        $wallet->increment('balance', $amount);

        if ($type === 'deposit') {
            $wallet->increment('total_deposited', $amount);
        }
        if ($type === 'cashback') {
            $wallet->increment('cashback_earned', $amount);
        }

        return WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $wallet->user_id,
            'amount' => $amount,
            'balance_before' => $before,
            'balance_after' => (float) $wallet->balance,
            'type' => $type,
            'description' => $description,
            'reference' => $reference,
            'status' => 'completed',
            'withdrawable' => $withdrawable,
        ]);
    }

    /**
     * Debit a wallet: drop balance, log the ledger row. The debited amount is
     * removed from the withdrawable pool too, so it cannot be withdrawn twice.
     */
    public static function debit(
        Wallet $wallet,
        float $amount,
        string $type,
        string $description,
        ?string $reference = null
    ): WalletTransaction {
        $amount = MoneyService::round($amount);
        $before = (float) $wallet->balance;

        // Never let a debit push the balance negative — the requestWithdrawal
        // path pre-validates, but this guard makes the service safe to reuse.
        if ($amount > $before) {
            throw new \InvalidArgumentException('Insufficient wallet balance.');
        }

        $wallet->decrement('balance', $amount);

        if ($type === 'withdrawal') {
            $wallet->increment('total_withdrawn', $amount);
        }

        return WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $wallet->user_id,
            'amount' => $amount,
            'balance_before' => $before,
            'balance_after' => (float) $wallet->balance,
            'type' => $type,
            'description' => $description,
            'reference' => $reference,
            'status' => 'completed',
            'withdrawable' => false,
        ]);
    }

    /**
     * Refund 100% of what the customer paid on a cancelled order into the
     * wallet. Marked withdrawable: the product spec defines the 10%-fee
     * withdrawal rule for cancellation refunds (refund-then-withdraw flow).
     * Flip to `false` here to make refunds spend-only store credit instead —
     * the flag is deliberately per-type and isolated to this one line.
     */
    public static function refundForCancellation(Order $order): ?WalletTransaction
    {
        $amount = MoneyService::round((float) $order->paid_amount);

        if ($amount <= 0) {
            return null;
        }

        // Idempotency guard: the same order can never be refunded twice, even
        // if a status flip lets cancelOrder run again.
        $reference = 'REFUND-' . $order->order_number;
        if (WalletTransaction::where('reference', $reference)->where('type', 'refund')->exists()) {
            return null;
        }

        $wallet = self::walletFor($order->user);

        return self::credit(
            $wallet,
            $amount,
            'refund',
            'Refund for cancelled order #' . $order->order_number,
            true, // withdrawable — 10% fee applies if moved to a bank
            $reference
        );
    }

    /**
     * Flat withdrawal processing fee on an amount (default 10%).
     */
    public static function withdrawalFeePercent(): float
    {
        return (float) (Setting::first()?->withdrawal_fee_percent ?? 10);
    }

    public static function withdrawalFee(float $amount): float
    {
        return MoneyService::round($amount * (self::withdrawalFeePercent() / 100));
    }

    /**
     * The 90% (amount − fee) actually sent to the bank.
     */
    public static function netWithdrawal(float $amount): float
    {
        return MoneyService::round($amount - self::withdrawalFee($amount));
    }

    /**
     * Whether a top-up is withdrawable (admin setting, default off).
     */
    public static function topUpWithdrawalAllowed(): bool
    {
        return (bool) (Setting::first()?->allow_topup_withdrawal ?? false);
    }

    /**
     * Bonus store credit granted on a top-up (admin config, default 0%).
     */
    public static function topUpBonus(float $amount): float
    {
        $percent = (float) (Setting::first()?->topup_bonus_percent ?? 0);

        return MoneyService::round($amount * ($percent / 100));
    }

    /**
     * Create a withdrawal request. Validates the amount against the
     * withdrawable pool, deducts it from the balance immediately (so it can't
     * be double-spent while the request is pending), and logs the request for
     * admin review.
     *
     * @throws \InvalidArgumentException when validation fails
     */
    public static function requestWithdrawal(
        User $user,
        float $amount,
        BankAccount $bank
    ): WalletWithdrawalRequest {
        $amount = MoneyService::round($amount);

        if ($bank->user_id !== $user->id) {
            throw new \InvalidArgumentException('That bank account does not belong to you.');
        }

        if ($amount <= 0) {
            throw new \InvalidArgumentException('Withdrawal amount must be positive.');
        }

        if ($amount < 100) {
            throw new \InvalidArgumentException('Minimum withdrawal is ₦100.');
        }

        $fee = self::withdrawalFee($amount);
        $net = self::netWithdrawal($amount);
        $reference = 'WDR-' . strtoupper(Str::random(12));

        // Money-sensitive: check the withdrawable pool and hold the funds inside
        // one transaction with a row lock, so a double-click (or two tabs) can
        // never both pass the check and over-withdraw.
        return DB::transaction(function () use ($user, $amount, $bank, $fee, $net, $reference) {
            $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first()
                ?? self::walletFor($user);

            $withdrawable = self::withdrawableBalance($wallet);

            if ($amount > $withdrawable) {
                throw new \InvalidArgumentException('Amount exceeds your withdrawable balance.');
            }

            // Hold the funds: deduct now, credit back if the request is failed.
            self::debit(
                $wallet,
                $amount,
                'withdrawal',
                'Withdrawal to ' . $bank->bank_name . ' ••••' . substr($bank->account_number, -4),
                $reference
            );

            return WalletWithdrawalRequest::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'bank_account_id' => $bank->id,
                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => $net,
                'status' => 'pending',
                'reference' => $reference,
            ]);
        });
    }

    /**
     * Admin credits a customer's wallet (cashback / rewards / store credit),
     * choosing withdrawable or not at the time of crediting.
     */
    public static function manualCredit(
        User $user,
        float $amount,
        string $type,
        string $description,
        bool $withdrawable
    ): WalletTransaction {
        $wallet = self::walletFor($user);

        return self::credit(
            $wallet,
            MoneyService::round($amount),
            $type,
            $description,
            $withdrawable,
            'ADMIN-' . strtoupper(Str::random(10))
        );
    }

    /**
     * Reverse a failed withdrawal: return the held funds to the balance. The
     * reversal is a ledger row typed 'refund' so the money stays non-withdrawable
     * by default (the admin-credit path owns the withdrawable choice).
     */
    public static function failWithdrawal(WalletWithdrawalRequest $request, ?string $note = null): void
    {
        $request->update([
            'status' => 'failed',
            'admin_note' => $note,
            'processed_at' => now(),
        ]);

        self::credit(
            $request->wallet,
            (float) $request->amount,
            'refund',
            'Reversal of failed withdrawal #' . $request->id,
            true, // restore to the withdrawable pool it came from
            'REV-' . $request->reference
        );
    }

    /**
     * Complete a withdrawal — the held funds are gone, nothing to return.
     */
    public static function completeWithdrawal(WalletWithdrawalRequest $request, ?string $note = null): void
    {
        $request->update([
            'status' => 'completed',
            'admin_note' => $note,
            'processed_at' => now(),
        ]);
    }
}
