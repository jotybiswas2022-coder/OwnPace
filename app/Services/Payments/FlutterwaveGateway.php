<?php

namespace App\Services\Payments;

use App\Models\PaymentTransaction;
use App\Services\Payments\Concerns\ReadsGatewayConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FlutterwaveGateway implements PaymentGatewayInterface
{
    use ReadsGatewayConfig;

    public function name(): string
    {
        return 'flutterwave';
    }

    public function initialize(PaymentTransaction $transaction): array
    {
        $config = $this->gatewayConfig([
            'flutterwave_secret' => 'FLUTTERWAVE_SECRET_KEY',
        ]);

        $response = Http::withToken($config['flutterwave_secret'])
            ->post('https://api.flutterwave.com/v3/payments', [
                'tx_ref' => $transaction->transaction_reference,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'redirect_url' => route('payment.flutterwave.callback'),
                'customer' => [
                    'email' => $transaction->user?->email,
                    'name' => $transaction->user?->name,
                ],
                'meta' => [
                    'transaction_id' => $transaction->id,
                ],
            ]);

        if ($response->successful() && ($response['status'] ?? null) === 'success') {
            $transaction->update([
                'gateway_reference' => $response['data']['id'] ?? null,
                'gateway' => $this->name(),
            ]);

            return [
                'success' => true,
                'url' => $response['data']['link'] ?? null,
                'message' => null,
            ];
        }

        return [
            'success' => false,
            'url' => null,
            'message' => $response['message'] ?? 'Flutterwave initialization failed',
        ];
    }

    public function verify(PaymentTransaction $transaction, array $payload): array
    {
        $config = $this->gatewayConfig([
            'flutterwave_secret' => 'FLUTTERWAVE_SECRET_KEY',
        ]);

        $reference = $payload['id'] ?? $payload['transaction_id'] ?? $transaction->gateway_reference;

        $response = Http::withToken($config['flutterwave_secret'])
            ->get("https://api.flutterwave.com/v3/transactions/{$reference}/verify");

        $data = $response->json() ?? [];
        $success = $response->successful()
            && ($data['status'] ?? null) === 'success'
            && ($data['data']['status'] ?? null) === 'successful';

        return ['success' => $success, 'data' => $data];
    }

    public function parseWebhook(Request $request): array
    {
        $data = $request->input('data', []);

        return [
            'reference' => $data['tx_ref'] ?? null,
            'success' => $request->input('event') === 'charge.completed' && ($data['status'] ?? null) === 'successful',
            'data' => $data,
        ];
    }
}
