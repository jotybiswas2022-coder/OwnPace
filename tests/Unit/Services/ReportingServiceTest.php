<?php

namespace Tests\Unit\Services;

use App\Models\InstallmentPayment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Services\Reporting\ReportingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportingServiceTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        return User::factory()->create();
    }

    private function orderFor(User $user, float $total = 1000, string $status = 'partial_paid'): Order
    {
        return Order::create([
            'order_number' => 'ORD-'.uniqid(),
            'user_id' => $user->id,
            'status' => $status,
            'grand_total' => $total,
            'paid_amount' => $total,
            'remaining_amount' => 0,
            'payment_type' => 'installment',
        ]);
    }

    public function test_sales_series_returns_daily_buckets_and_totals(): void
    {
        $user = $this->user();
        $order = $this->orderFor($user, 5000);
        PaymentTransaction::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'transaction_reference' => 'TXN-'.uniqid(),
            'gateway' => 'paystack',
            'amount' => 5000,
            'currency' => 'NGN',
            'status' => 'success',
            'type' => 'payment',
        ]);

        $sales = ReportingService::sales(7);

        $this->assertCount(7, $sales['labels']);
        $this->assertEquals(5000, $sales['revenueTotal']);
        $this->assertEquals(1, $sales['orderTotal']);
        $this->assertEquals(5000, $sales['aov']);
    }

    public function test_sales_excludes_failed_transactions_and_cancelled_orders(): void
    {
        $user = $this->user();
        $order = $this->orderFor($user, 900, 'cancelled');
        PaymentTransaction::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'transaction_reference' => 'TXN-'.uniqid(),
            'gateway' => 'paystack',
            'amount' => 900,
            'currency' => 'NGN',
            'status' => 'success',
            'type' => 'payment',
        ]);

        $sales = ReportingService::sales(7);

        $this->assertEquals(900, $sales['revenueTotal']); // money still counted
        $this->assertEquals(0, $sales['orderTotal']);     // cancelled order excluded
    }

    public function test_installment_performance_buckets_on_time_vs_late(): void
    {
        $user = $this->user();
        $order = $this->orderFor($user);

        InstallmentPayment::create([
            'order_id' => $order->id,
            'installment_number' => 1,
            'amount' => 100,
            'due_date' => now()->subDays(10),
            'paid_date' => now()->subDays(10),  // paid exactly on time
            'status' => 'paid',
            'paid_amount' => 100,
        ]);
        InstallmentPayment::create([
            'order_id' => $order->id,
            'installment_number' => 2,
            'amount' => 100,
            'due_date' => now()->subDays(10),
            'paid_date' => now()->subDays(5),   // paid late
            'status' => 'paid',
            'paid_amount' => 100,
        ]);
        InstallmentPayment::create([
            'order_id' => $order->id,
            'installment_number' => 3,
            'amount' => 100,
            'due_date' => now()->subDays(40),   // defaulted (>30d past due)
            'status' => 'overdue',
            'paid_amount' => 0,
        ]);

        $data = ReportingService::installmentPerformance();

        $this->assertSame(1, $data['breakdown']['on_time']['count']);
        $this->assertSame(1, $data['breakdown']['late']['count']);
        $this->assertSame(0, $data['breakdown']['overdue']['count']);
        $this->assertSame(1, $data['breakdown']['defaulted']['count']);
        $this->assertCount(12, $data['months']);
    }

    public function test_repeat_purchase_rate_and_aov(): void
    {
        $repeat = $this->user();
        $once = $this->user();

        $this->orderFor($repeat, 1000);
        $this->orderFor($repeat, 2000);
        $this->orderFor($once, 3000);

        // Revenue is measured from successful payment transactions.
        $orders = Order::all();
        foreach ($orders as $index => $order) {
            PaymentTransaction::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'transaction_reference' => 'TXN-'.uniqid(),
                'gateway' => 'paystack',
                'amount' => $order->grand_total,
                'currency' => 'NGN',
                'status' => 'success',
                'type' => 'payment',
            ]);
        }

        $behavior = ReportingService::customerBehavior();

        $this->assertSame(2, $behavior['buyers']);
        $this->assertSame(1, $behavior['repeatBuyers']);
        $this->assertSame(50.0, $behavior['repeatRate']);
        $this->assertEquals(2000.0, $behavior['aov']); // 6000 / 3 orders
    }

    public function test_top_products_sums_units_and_revenue(): void
    {
        $user = $this->user();
        $order = $this->orderFor($user, 500);

        $product = \App\Models\Product::create([
            'name' => 'Smartphone',
            'slug' => 'smartphone-'.uniqid(),
            'price' => 250,
            'base_price' => 250,
            'status' => 'active',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => 'Smartphone',
            'unit_price' => 250,
            'quantity' => 2,
            'subtotal' => 500,
        ]);

        $behavior = ReportingService::customerBehavior();

        $this->assertSame('Smartphone', $behavior['topProducts'][0]->label);
        $this->assertSame(2, (int) $behavior['topProducts'][0]->units);
        $this->assertEquals(500, (float) $behavior['topProducts'][0]->revenue);
    }
}
