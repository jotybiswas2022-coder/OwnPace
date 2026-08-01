<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use App\Models\Order;
use App\Models\InstallmentPayment;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Setting;
use App\Services\InstallmentScheduleService;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    // ===== PAYSTACK =====
    public function paystackInitialize(Request $request)
    {
        $request->validate([
            'transaction_reference' => 'required|exists:payment_transactions,transaction_reference',
        ]);

        $transaction = PaymentTransaction::where('transaction_reference', $request->transaction_reference)
            ->firstOrFail();

        $settings = Setting::first();
        $gatewayConfig = json_decode($settings?->gateway_config ?? '{}', true);
        $secretKey = $gatewayConfig['paystack_secret'] ?? env('PAYSTACK_SECRET_KEY');

        $response = Http::withToken($secretKey)
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => auth()->user()->email,
                'amount' => $transaction->amount * 100, // Paystack uses kobo
                'reference' => $transaction->transaction_reference,
                'callback_url' => route('payment.paystack.callback'),
                'metadata' => [
                    'transaction_id' => $transaction->id,
                    'user_id' => auth()->id(),
                ],
            ]);

        if ($response->successful() && $response['status']) {
            $transaction->update([
                'gateway_reference' => $response['data']['reference'],
                'gateway' => 'paystack',
            ]);

            return response()->json([
                'success' => true,
                'authorization_url' => $response['data']['authorization_url'],
            ]);
        }

        return response()->json(['success' => false, 'message' => $response['message'] ?? 'Payment initialization failed'], 400);
    }

    public function paystackCallback(Request $request)
    {
        $reference = $request->reference;
        $transaction = PaymentTransaction::where('transaction_reference', $reference)->first();

        if (!$transaction) {
            return redirect('/')->with('error', 'Transaction not found.');
        }

        $settings = Setting::first();
        $gatewayConfig = json_decode($settings?->gateway_config ?? '{}', true);
        $secretKey = $gatewayConfig['paystack_secret'] ?? env('PAYSTACK_SECRET_KEY');

        $response = Http::withToken($secretKey)
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($response->successful() && $response['status'] && $response['data']['status'] === 'success') {
            return $this->completePayment($transaction, $response['data']);
        }

        $transaction->update(['status' => 'failed', 'gateway_response' => $response->json()]);
        return redirect()->route('profile.index')->with('error', 'Payment verification failed.');
    }

    // ===== FLUTTERWAVE =====
    public function flutterwaveInitialize(Request $request)
    {
        $request->validate([
            'transaction_reference' => 'required|exists:payment_transactions,transaction_reference',
        ]);

        $transaction = PaymentTransaction::where('transaction_reference', $request->transaction_reference)
            ->firstOrFail();

        $settings = Setting::first();
        $gatewayConfig = json_decode($settings?->gateway_config ?? '{}', true);
        $secretKey = $gatewayConfig['flutterwave_secret'] ?? env('FLUTTERWAVE_SECRET_KEY');

        $response = Http::withToken($secretKey)
            ->post('https://api.flutterwave.com/v3/payments', [
                'tx_ref' => $transaction->transaction_reference,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'redirect_url' => route('payment.flutterwave.callback'),
                'customer' => [
                    'email' => auth()->user()->email,
                    'name' => auth()->user()->name,
                ],
                'meta' => [
                    'transaction_id' => $transaction->id,
                ],
            ]);

        if ($response->successful() && $response['status'] === 'success') {
            $transaction->update([
                'gateway_reference' => $response['data']['id'] ?? null,
                'gateway' => 'flutterwave',
            ]);

            return response()->json([
                'success' => true,
                'link' => $response['data']['link'],
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Payment initialization failed'], 400);
    }

    public function flutterwaveCallback(Request $request)
    {
        $transactionId = $request->transaction_id;
        $transaction = PaymentTransaction::find($transactionId);

        if (!$transaction) {
            return redirect('/')->with('error', 'Transaction not found.');
        }

        $settings = Setting::first();
        $gatewayConfig = json_decode($settings?->gateway_config ?? '{}', true);
        $secretKey = $gatewayConfig['flutterwave_secret'] ?? env('FLUTTERWAVE_SECRET_KEY');

        $response = Http::withToken($secretKey)
            ->get("https://api.flutterwave.com/v3/transactions/{$transaction->gateway_reference}/verify");

        if ($response->successful() && $response['status'] === 'success' && $response['data']['status'] === 'successful') {
            return $this->completePayment($transaction, $response['data']);
        }

        $transaction->update(['status' => 'failed', 'gateway_response' => $response->json()]);
        return redirect()->route('profile.index')->with('error', 'Payment verification failed.');
    }

    // ===== KORAPAY =====
    public function korapayInitialize(Request $request)
    {
        $request->validate([
            'transaction_reference' => 'required|exists:payment_transactions,transaction_reference',
        ]);

        $transaction = PaymentTransaction::where('transaction_reference', $request->transaction_reference)
            ->firstOrFail();

        $settings = Setting::first();
        $gatewayConfig = json_decode($settings?->gateway_config ?? '{}', true);
        $secretKey = $gatewayConfig['korapay_secret'] ?? env('KORAPAY_SECRET_KEY');

        $response = Http::withToken($secretKey)
            ->post('https://api.korapay.com/merchant/api/v1/charges/initialize', [
                'reference' => $transaction->transaction_reference,
                'amount' => $transaction->amount * 100, // Korapay uses kobo
                'currency' => $transaction->currency,
                'redirect_url' => route('payment.korapay.callback'),
                'customer' => [
                    'email' => auth()->user()->email,
                    'name' => auth()->user()->name,
                ],
            ]);

        if ($response->successful() && $response['status']) {
            $transaction->update([
                'gateway_reference' => $response['data']['reference'],
                'gateway' => 'korapay',
            ]);

            return response()->json([
                'success' => true,
                'checkout_url' => $response['data']['checkout_url'],
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Payment initialization failed'], 400);
    }

    public function korapayCallback(Request $request)
    {
        $reference = $request->reference;
        $transaction = PaymentTransaction::where('transaction_reference', $reference)->first();

        if (!$transaction) {
            return redirect('/')->with('error', 'Transaction not found.');
        }

        $settings = Setting::first();
        $gatewayConfig = json_decode($settings?->gateway_config ?? '{}', true);
        $secretKey = $gatewayConfig['korapay_secret'] ?? env('KORAPAY_SECRET_KEY');

        $response = Http::withToken($secretKey)
            ->get("https://api.korapay.com/merchant/api/v1/charges/{$reference}");

        if ($response->successful() && $response['status'] && $response['data']['status'] === 'success') {
            return $this->completePayment($transaction, $response['data']);
        }

        $transaction->update(['status' => 'failed', 'gateway_response' => $response->json()]);
        return redirect()->route('profile.index')->with('error', 'Payment verification failed.');
    }

    // ===== COMPLETE PAYMENT =====
    private function completePayment(PaymentTransaction $transaction, $gatewayData)
    {
        $transaction->update([
            'status' => 'success',
            'gateway_response' => $gatewayData,
        ]);

        // Handle wallet funding
        if ($transaction->type === 'wallet_funding') {
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $transaction->user_id],
                ['balance' => 0]
            );
            $balanceBefore = $wallet->balance;
            $wallet->increment('balance', $transaction->amount);
            $wallet->increment('total_deposited', $transaction->amount);

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $transaction->user_id,
                'amount' => $transaction->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $wallet->balance,
                'type' => 'deposit',
                'description' => 'Wallet funding via ' . $transaction->gateway,
                'reference' => $transaction->transaction_reference,
                'status' => 'completed',
            ]);

            return redirect()->route('wallet.index')->with('success', 'Wallet funded successfully!');
        }

        // Handle order payment
        if ($transaction->order_id) {
            $order = Order::find($transaction->order_id);
            if ($order) {
                // Single source of truth for the money math — advances paid/remaining
                // exactly once and keeps the remaining schedule summing to the balance.
                $installmentPayment = $transaction->installment_payment_id
                    ? InstallmentPayment::find($transaction->installment_payment_id)
                    : null;

                InstallmentScheduleService::recordPayment(
                    $order,
                    (float) $transaction->amount,
                    $transaction->gateway,
                    $installmentPayment
                );

                // Delivery unlocks at the 70% payment threshold (grand_total based).
                $order = $order->fresh();
                $threshold = ($order->grand_total * 70) / 100;
                if ((float) $order->paid_amount >= $threshold && $order->delivery_status === 'pending') {
                    $order->update(['delivery_status' => 'processing']);
                }

                return redirect()->route('order.confirmation', $order->id)
                    ->with('success', 'Payment successful!');
            }
        }

        return redirect()->route('profile.index')->with('success', 'Payment completed!');
    }

    // ===== WEBHOOKS =====
    public function paystackWebhook(Request $request)
    {
        $event = $request->event;
        if ($event === 'charge.success') {
            $reference = $request->data['reference'];
            $transaction = PaymentTransaction::where('transaction_reference', $reference)->first();
            if ($transaction && $transaction->status === 'pending') {
                $this->completePayment($transaction, $request->data);
            }
        }
        return response()->json(['status' => 'ok']);
    }

    public function flutterwaveWebhook(Request $request)
    {
        if ($request->event === 'charge.completed' && $request->data['status'] === 'successful') {
            $txRef = $request->data['tx_ref'];
            $transaction = PaymentTransaction::where('transaction_reference', $txRef)->first();
            if ($transaction && $transaction->status === 'pending') {
                $this->completePayment($transaction, $request->data);
            }
        }
        return response()->json(['status' => 'ok']);
    }

    public function korapayWebhook(Request $request)
    {
        if ($request->event === 'charge.success') {
            $reference = $request->data['reference'];
            $transaction = PaymentTransaction::where('transaction_reference', $reference)->first();
            if ($transaction && $transaction->status === 'pending') {
                $this->completePayment($transaction, $request->data);
            }
        }
        return response()->json(['status' => 'ok']);
    }
}
