<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use App\Models\Order;
use App\Models\InstallmentPayment;
use App\Services\InstallmentScheduleService;
use App\Services\Payments\PaymentGatewayManager;

/**
 * PaymentController — gateway-agnostic. Every provider-specific call is
 * delegated to the PaymentGatewayInterface adapters via PaymentGatewayManager,
 * so adding or removing a gateway is a one-file change.
 */
class PaymentController extends Controller
{
    public function __construct(private PaymentGatewayManager $gateways)
    {
    }

    // ===== INITIALIZE (shared by all gateways) =====
    public function initialize(Request $request)
    {
        $request->validate([
            'transaction_reference' => 'required|exists:payment_transactions,transaction_reference',
            'gateway' => 'required|string',
        ]);

        $transaction = PaymentTransaction::where('transaction_reference', $request->transaction_reference)
            ->firstOrFail();

        if (!$this->gateways->has($request->gateway)) {
            return response()->json(['success' => false, 'message' => 'Unknown gateway'], 400);
        }

        $result = $this->gateways->driver($request->gateway)->initialize($transaction);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'url' => $result['url'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Payment initialization failed',
        ], 400);
    }

    // ===== CALLBACKS =====
    public function paystackCallback(Request $request)
    {
        $transaction = PaymentTransaction::where('transaction_reference', $request->reference)->first();

        if (!$transaction) {
            return redirect('/')->with('error', 'Transaction not found.');
        }

        $result = $this->gateways->driver('paystack')->verify($transaction, $request->all());

        if ($result['success']) {
            return $this->completePayment($transaction, $result['data']);
        }

        $transaction->update(['status' => 'failed', 'gateway_response' => $result['data']]);
        return redirect()->route('profile.index')->with('error', 'Payment verification failed.');
    }

    public function flutterwaveCallback(Request $request)
    {
        $transaction = PaymentTransaction::find($request->transaction_id);

        if (!$transaction) {
            return redirect('/')->with('error', 'Transaction not found.');
        }

        $result = $this->gateways->driver('flutterwave')->verify($transaction, $request->all());

        if ($result['success']) {
            return $this->completePayment($transaction, $result['data']);
        }

        $transaction->update(['status' => 'failed', 'gateway_response' => $result['data']]);
        return redirect()->route('profile.index')->with('error', 'Payment verification failed.');
    }

    public function korapayCallback(Request $request)
    {
        $transaction = PaymentTransaction::where('transaction_reference', $request->reference)->first();

        if (!$transaction) {
            return redirect('/')->with('error', 'Transaction not found.');
        }

        $result = $this->gateways->driver('korapay')->verify($transaction, $request->all());

        if ($result['success']) {
            return $this->completePayment($transaction, $result['data']);
        }

        $transaction->update(['status' => 'failed', 'gateway_response' => $result['data']]);
        return redirect()->route('profile.index')->with('error', 'Payment verification failed.');
    }

    // ===== WEBHOOKS =====
    public function handleWebhook(Request $request, string $gateway)
    {
        if (!$this->gateways->has($gateway)) {
            return response()->json(['status' => 'error'], 400);
        }

        $parsed = $this->gateways->driver($gateway)->parseWebhook($request);

        if ($parsed['success'] && $parsed['reference']) {
            $transaction = PaymentTransaction::where('transaction_reference', $parsed['reference'])->first();
            if ($transaction && $transaction->status === 'pending') {
                $this->completePayment($transaction, $parsed['data']);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    // ===== COMPLETE PAYMENT =====
    private function completePayment(PaymentTransaction $transaction, array $gatewayData)
    {
        $transaction->update([
            'status' => 'success',
            'gateway_response' => $gatewayData,
        ]);

        // Handle wallet funding
        if ($transaction->type === 'wallet_funding') {
            $user = $transaction->user;
            $wallet = \App\Services\WalletService::walletFor($user);

            // Top-ups are withdrawable only when the admin setting allows it
            // (client confirmed: default OFF — spend-only store credit).
            \App\Services\WalletService::credit(
                $wallet,
                (float) $transaction->amount,
                'deposit',
                'Wallet funding via ' . $transaction->gateway,
                \App\Services\WalletService::topUpWithdrawalAllowed(),
                $transaction->transaction_reference
            );

            // Admin-configurable bonus store credit on top-ups (default 0%).
            $bonus = \App\Services\WalletService::topUpBonus((float) $transaction->amount);
            if ($bonus > 0) {
                \App\Services\WalletService::credit(
                    $wallet,
                    $bonus,
                    'bonus',
                    'Top-up bonus (' . \App\Services\MoneyService::plain((float) (\App\Models\Setting::first()?->topup_bonus_percent ?? 0), 0) . '%)',
                    false,
                    $transaction->transaction_reference . '-BONUS'
                );
            }

            return redirect()->route('wallet.index')->with('success', 'Wallet funded successfully!');
        }

        // Handle order payment
        if ($transaction->order_id) {
            $order = Order::find($transaction->order_id);
            if ($order) {
                // Single source of truth for the money math — advances paid/remaining
                // exactly once, keeps the remaining schedule summing to the balance,
                // and flips delivery eligibility at the 70% threshold.
                $installmentPayment = $transaction->installment_payment_id
                    ? InstallmentPayment::find($transaction->installment_payment_id)
                    : null;

                InstallmentScheduleService::recordPayment(
                    $order,
                    (float) $transaction->amount,
                    $transaction->gateway,
                    $installmentPayment
                );

                return redirect()->route('order.confirmation', $order->id)
                    ->with('success', 'Payment successful!');
            }
        }

        return redirect()->route('profile.index')->with('success', 'Payment completed!');
    }
}
