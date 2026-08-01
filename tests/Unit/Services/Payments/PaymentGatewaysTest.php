<?php

namespace Tests\Unit\Services\Payments;

use App\Models\PaymentTransaction;
use App\Models\User;
use App\Services\Payments\FlutterwaveGateway;
use App\Services\Payments\KorapayGateway;
use App\Services\Payments\PaymentGatewayManager;
use App\Services\Payments\PaystackGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Tests\TestCase;

class PaymentGatewaysTest extends TestCase
{
    use RefreshDatabase;

    private function manager(): PaymentGatewayManager
    {
        return new PaymentGatewayManager([
            'paystack' => app(PaystackGateway::class),
            'flutterwave' => app(FlutterwaveGateway::class),
            'korapay' => app(KorapayGateway::class),
        ]);
    }

    private function transaction(): PaymentTransaction
    {
        $user = User::factory()->create(['email' => 'buyer@example.com', 'name' => 'Buyer']);

        return PaymentTransaction::create([
            'user_id' => $user->id,
            'transaction_reference' => 'TXN-TEST-' . uniqid(),
            'gateway' => 'paystack',
            'amount' => 5000,
            'currency' => 'NGN',
            'status' => 'pending',
        ]);
    }

    public function test_manager_resolves_registered_drivers(): void
    {
        $manager = $this->manager();

        $this->assertTrue($manager->has('paystack'));
        $this->assertInstanceOf(PaystackGateway::class, $manager->driver('paystack'));
        $this->assertInstanceOf(FlutterwaveGateway::class, $manager->driver('flutterwave'));
        $this->assertInstanceOf(KorapayGateway::class, $manager->driver('korapay'));
        $this->assertFalse($manager->has('paypal'));
    }

    public function test_manager_throws_for_unknown_driver(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->manager()->driver('paypal');
    }

    public function test_paystack_initialize_returns_authorization_url(): void
    {
        Http::fake([
            'api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'message' => 'Authorization URL created',
                'data' => ['reference' => 'REF-1', 'authorization_url' => 'https://checkout.paystack.com/abc'],
            ]),
        ]);

        $result = app(PaystackGateway::class)->initialize($this->transaction());

        $this->assertTrue($result['success']);
        $this->assertEquals('https://checkout.paystack.com/abc', $result['url']);
    }

    public function test_paystack_initialize_failure_returns_message(): void
    {
        Http::fake([
            'api.paystack.co/transaction/initialize' => Http::response([
                'status' => false,
                'message' => 'Invalid key',
            ], 400),
        ]);

        $result = app(PaystackGateway::class)->initialize($this->transaction());

        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid key', $result['message']);
    }

    public function test_paystack_verify_returns_success_for_completed_charge(): void
    {
        Http::fake([
            'api.paystack.co/transaction/verify/*' => Http::response([
                'status' => true,
                'data' => ['status' => 'success', 'amount' => 500000],
            ]),
        ]);

        $result = app(PaystackGateway::class)->verify($this->transaction(), []);

        $this->assertTrue($result['success']);
    }

    public function test_paystack_webhook_parses_charge_success(): void
    {
        $gateway = app(PaystackGateway::class);
        $request = Request::create('/payment/webhook/paystack', 'POST', [
            'event' => 'charge.success',
            'data' => ['reference' => 'REF-1'],
        ]);

        $parsed = $gateway->parseWebhook($request);

        $this->assertTrue($parsed['success']);
        $this->assertEquals('REF-1', $parsed['reference']);
    }

    public function test_flutterwave_initialize_returns_payment_link(): void
    {
        Http::fake([
            'api.flutterwave.com/v3/payments' => Http::response([
                'status' => 'success',
                'message' => 'Hosted Payment',
                'data' => ['id' => 1, 'link' => 'https://checkout.flutterwave.com/xyz'],
            ]),
        ]);

        $result = app(FlutterwaveGateway::class)->initialize($this->transaction());

        $this->assertTrue($result['success']);
        $this->assertEquals('https://checkout.flutterwave.com/xyz', $result['url']);
    }

    public function test_flutterwave_verify_requires_successful_status(): void
    {
        Http::fake([
            'api.flutterwave.com/v3/transactions/*/verify' => Http::response([
                'status' => 'success',
                'data' => ['status' => 'successful'],
            ]),
        ]);

        $result = app(FlutterwaveGateway::class)->verify($this->transaction(), ['id' => 1]);

        $this->assertTrue($result['success']);
    }

    public function test_korapay_initialize_returns_checkout_url(): void
    {
        Http::fake([
            'api.korapay.com/merchant/api/v1/charges/initialize' => Http::response([
                'status' => true,
                'message' => 'Charge created',
                'data' => ['reference' => 'REF-K', 'checkout_url' => 'https://checkout.korapay.com/zzz'],
            ]),
        ]);

        $result = app(KorapayGateway::class)->initialize($this->transaction());

        $this->assertTrue($result['success']);
        $this->assertEquals('https://checkout.korapay.com/zzz', $result['url']);
    }

    public function test_korapay_webhook_parses_charge_success(): void
    {
        $gateway = app(KorapayGateway::class);
        $request = Request::create('/payment/webhook/korapay', 'POST', [
            'event' => 'charge.success',
            'data' => ['reference' => 'REF-K'],
        ]);

        $parsed = $gateway->parseWebhook($request);

        $this->assertTrue($parsed['success']);
        $this->assertEquals('REF-K', $parsed['reference']);
    }
}
