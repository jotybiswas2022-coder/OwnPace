<?php

namespace App\Services\Payments;

use App\Models\PaymentTransaction;
use App\Services\Payments\Concerns\ReadsGatewayConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaystackGateway implements PaymentGatewayInterface
{
    use ReadsGatewayConfig;

    public function name(): string
    {
        return 'paystack';
    }

    public function initialize(PaymentTransaction $transaction): array
    {
        $config = $this->gatewayConfig([
            'paystack_secret' => 'PAYSTACK_SECRET_KEY',
        ]);

        $response = Http::withToken($config['paystack_secret'])
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => $transaction->user?->email,
                'amount' => (int) round($transaction->amount * 100), // kobo
                'reference' => $transaction->transaction_reference,
                'callback_url' => route('payment.paystack.callback'),
                'metadata' => [
                    'transaction_id' => $transaction->id,
                    'user_id' => $transaction->user_id,
                ],
            ]);

        if ($response->successful() && $response['status']) {
            $transaction->update([
                'gateway_reference' => $response['data']['reference'],
                'gateway' => $this->name(),
            ]);

            return [
                'success' => true,
                'url' => $response['data']['authorization_url'],
                'message' => null,
            ];
        }

        return [
            'success' => false,
            'url' => null,
            'message' => $response['message'] ?? 'Paystack initialization failed',
        ];
    }

    public function verify(PaymentTransaction $transaction, array $payload): array
    {
        $config = $this->gatewayConfig([
            'paystack_secret' => 'PAYSTACK_SECRET_KEY',
        ]);

        $reference = $transaction->transaction_reference;

        $response = Http::withToken($config['paystack_secret'])
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        $success = $response->successful()
            && ($response['status'] ?? false)
            && ($response['data']['status'] ?? null) === 'success';

        return [
            'success' => $success,
            'data' => $response->json() ?? [],
        ];
    }

    public function parseWebhook(Request $request): array
    {
        $event = $request->input('event');
        $data = $request->input('data', []);

        return [
            'reference' => $data['reference'] ?? null,
            'success' => $event === 'charge.success',
            'data' => $data,
        ];
    }
}
