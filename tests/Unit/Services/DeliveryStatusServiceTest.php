<?php

namespace Tests\Unit\Services;

use App\Models\DeliveryTracking;
use App\Models\InstallmentPlan;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Services\DeliveryStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeliveryStatusServiceTest extends TestCase
{
    use RefreshDatabase;

    private function order(float $paid, float $grand = 1000, string $deliveryStatus = 'pending'): Order
    {
        $user = User::factory()->create();

        return Order::create([
            'order_number' => 'ORD-ELIG-' . uniqid(),
            'user_id' => $user->id,
            'status' => 'partial_paid',
            'total_amount' => $grand,
            'base_amount' => $grand,
            'grand_total' => $grand,
            'paid_amount' => $paid,
            'remaining_amount' => max(0, $grand - $paid),
            'payment_type' => 'installment',
            'delivery_status' => $deliveryStatus,
        ]);
    }

    public function test_threshold_defaults_to_70(): void
    {
        $this->assertEquals(70.0, DeliveryStatusService::thresholdPercent());
    }

    public function test_threshold_reads_from_settings(): void
    {
        Setting::create(['store_name' => 'OwnPace', 'delivery_threshold_percentage' => 50]);

        $this->assertEquals(50.0, DeliveryStatusService::thresholdPercent());
    }

    public function test_evaluate_flips_to_eligible_at_threshold_and_logs_event(): void
    {
        $order = $this->order(700, 1000); // exactly 70%

        DeliveryStatusService::evaluate($order);

        $this->assertEquals('eligible', $order->fresh()->delivery_status);
        $this->assertEquals(1, DeliveryTracking::where('order_id', $order->id)->where('status', 'eligible')->count());
    }

    public function test_evaluate_does_nothing_below_threshold(): void
    {
        $order = $this->order(600, 1000); // 60%

        DeliveryStatusService::evaluate($order);

        $this->assertEquals('pending', $order->fresh()->delivery_status);
        $this->assertEquals(0, DeliveryTracking::count());
    }

    public function test_evaluate_skips_orders_already_past_the_gate(): void
    {
        $order = $this->order(900, 1000, 'shipped');

        DeliveryStatusService::evaluate($order);

        $this->assertEquals('shipped', $order->fresh()->delivery_status);
    }

    public function test_transition_records_status_and_delivered_at(): void
    {
        $order = $this->order(1000, 1000, 'eligible');

        DeliveryStatusService::transition($order, 'delivered');

        $fresh = $order->fresh();
        $this->assertEquals('delivered', $fresh->delivery_status);
        $this->assertNotNull($fresh->delivered_at);
        $this->assertEquals(1, DeliveryTracking::where('order_id', $order->id)->where('status', 'delivered')->count());
    }

    public function test_record_payment_unlocks_delivery_at_threshold(): void
    {
        // A real payment path: 4 × ₦275 on a ₦1,100 installment plan.
        $plan = InstallmentPlan::create([
            'name' => '4 Weeks',
            'type' => 'weekly',
            'duration' => 4,
            'duration_days' => 28,
            'interest_rate' => 0,
            'is_active' => true,
        ]);
        $user = User::factory()->create();
        $order = Order::create([
            'order_number' => 'ORD-ELIG-' . uniqid(),
            'user_id' => $user->id,
            'installment_plan_id' => $plan->id,
            'status' => 'pending',
            'grand_total' => 1100,
            'total_amount' => 1100,
            'base_amount' => 1100,
            'paid_amount' => 0,
            'remaining_amount' => 1100,
            'payment_type' => 'installment',
            'delivery_status' => 'pending',
        ]);
        \App\Services\InstallmentScheduleService::createSchedule($order, $plan);

        // 3 of 4 installments = ₦825 = 75% → eligible.
        \App\Services\InstallmentScheduleService::recordPayment($order, 275, 'wallet', $order->installmentPayments()->first());
        \App\Services\InstallmentScheduleService::recordPayment($order->fresh(), 275, 'wallet', $order->fresh()->installmentPayments()->where('status', '!=', 'paid')->first());
        \App\Services\InstallmentScheduleService::recordPayment($order->fresh(), 275, 'wallet', $order->fresh()->installmentPayments()->where('status', '!=', 'paid')->first());

        $this->assertEquals('eligible', $order->fresh()->delivery_status);
        $this->assertEquals(825.0, (float) $order->fresh()->paid_amount);
    }
}
