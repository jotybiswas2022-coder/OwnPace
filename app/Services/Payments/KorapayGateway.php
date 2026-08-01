<?php

namespace App\Services\Payments;

use App\Models\PaymentTransaction;
use App\Services\Payments\Concerns\ReadsGatewayConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KorapayGateway implements PaymentGatewayInterface
{
    use ReadsGatewayConfig;

    public function name(): string
    {
        return 'korapay';
    }

    public function initialize(PaymentTransaction $transaction): array
    {
        $config = $this->gatewayConfig([
            'korapay_secret' => 'KORAPAY_SECRET_KEY',
        ]);

        $response = Http::withToken($config['korapay_secret'])
            ->post('https://api.korapay.com/merchant/api/v1/charges/initialize', [
                'reference' => $transaction->transaction_reference,
                'amount' => (int) round($transaction->amount * 100), // kobo
                'currency' => $transaction->currency,
                'redirect_url' => route('payment.korapay.callback'),
                'customer' => [
                    'email' => $transaction->user?->email,
                    'name' => $transaction->user?->name,
                ],
            ]);

        if ($response->successful() && ($response['status'] ?? false)) {
            $transaction->update([
                'gateway_reference' => $response['data']['reference'],
                'gateway' => $this->name(),
            ]);

            return [
                'success' => true,
                'url' => $response['data']['checkout_url'] ?? null,
                'message' => null,
            ];
        }

        return [
            'success' => false,
            'url' => null,
            'message' => $response['message'] ?? 'Korapay initialization failed',
        ];
    }

    public function verify(PaymentTransaction $transaction, array $payload): array
    {
        $config = $this->gatewayConfig([
            'korapay_secret' => 'KORAPAY_SECRET_KEY',
        ]);

        $reference = $transaction->transaction_reference;

        $response = Http::withToken($config['korapay_secret'])
            ->get("https://api.korapay.com/merchant/api/v1/charges/{$reference}");

        $data = $response->json() ?? [];
        $success = $response->successful()
            && ($data['status'] ?? false)
            && ($data['data']['status'] ?? null) === 'success';

        return ['success' => $success, 'data' => $data];
    }

    public function parseWebhook(Request $request): array
    {
        $data = $request->input('data', []);

        return [
            'reference' => $data['reference'] ?? null,
            'success' => $request->input('event') === 'charge.success',
            'data' => $data,
        ];
    }
}
