<?php

namespace Tests\Unit\Services;

use App\Models\InstallmentPayment;
use App\Models\InstallmentPlan;
use App\Models\Order;
use App\Models\User;
use App\Services\InstallmentScheduleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstallmentScheduleServiceTest extends TestCase
{
    use RefreshDatabase;

    private function plan(array $overrides = []): InstallmentPlan
    {
        return InstallmentPlan::create(array_merge([
            'name' => '4 Weeks',
            'type' => 'weekly',
            'duration' => 4,
            'duration_days' => 28,
            'interest_rate' => 10,
            'is_active' => true,
            'sort_order' => 4,
            'late_fee_enabled' => false,
            'late_fee_percent' => 0,
        ], $overrides));
    }

    private function order(InstallmentPlan $plan, float $grandTotal = 1100): Order
    {
        $user = User::factory()->create();

        return Order::create([
            'order_number' => 'ORD-TEST-' . uniqid(),
            'user_id' => $user->id,
            'installment_plan_id' => $plan->id,
            'status' => 'pending',
            'total_amount' => 1000,
            'base_amount' => 1000,
            'shipping_fee' => 0,
            'insurance_fee' => 0,
            'interest_amount' => 100,
            'grand_total' => $grandTotal,
            'paid_amount' => 0,
            'remaining_amount' => $grandTotal,
            'payment_type' => 'installment',
            'has_insurance' => false,
            'delivery_status' => 'pending',
        ]);
    }

    public function test_create_schedule_sums_exactly_to_grand_total(): void
    {
        $plan = $this->plan();
        $order = $this->order($plan, 1100);

        InstallmentScheduleService::createSchedule($order, $plan);

        $this->assertCount(4, $order->installmentPayments()->get());
        $this->assertEquals(1100.0, $order->installmentPayments()->sum('amount'));
        $this->assertEquals(275.0, (float) $order->installmentPayments()->first()->amount);
    }

    public function test_next_unpaid_returns_first_pending_in_order(): void
    {
        $plan = $this->plan();
        $order = $this->order($plan);
        InstallmentScheduleService::createSchedule($order, $plan);

        $order->installmentPayments()->first()->update(['status' => 'paid']);

        $next = InstallmentScheduleService::nextUnpaid($order->fresh());
        $this->assertEquals(2, $next->installment_number);
    }

    public function test_recalculate_redistributes_remaining_after_partial_payment(): void
    {
        $plan = $this->plan();
        $order = $this->order($plan, 1100);
        InstallmentScheduleService::createSchedule($order, $plan);

        // Customer pays a custom ₦200 toward the ₦1,100 total.
        InstallmentScheduleService::recordPayment($order, 200, 'gateway');

        $order = $order->fresh();
        $this->assertEquals(200.0, (float) $order->paid_amount);
        $this->assertEquals(900.0, (float) $order->remaining_amount);
        $this->assertEquals('partial_paid', $order->status);

        // 900 spread across 4 unpaid installments = 225 each.
        foreach ($order->installmentPayments()->orderBy('installment_number')->get() as $ip) {
            $this->assertEquals(225.0, (float) $ip->amount);
        }
        $this->assertEquals(900.0, $order->installmentPayments()->sum('amount'));
    }

    public function test_recalculate_last_row_absorbs_remainder(): void
    {
        $plan = $this->plan(['duration' => 12, 'interest_rate' => 22, 'name' => '12 Months']);
        $order = $this->order($plan, 7100);
        InstallmentScheduleService::createSchedule($order, $plan);

        // Odd partial payment leaves a remainder that can't split evenly.
        InstallmentScheduleService::recordPayment($order, 100, 'gateway');

        $rows = $order->fresh()->installmentPayments()->orderBy('installment_number')->get();
        $sum = $rows->sum('amount');
        $this->assertEquals(7000.0, $sum);

        $per = $rows->first()->amount;
        $last = $rows->last()->amount;
        $this->assertEqualsWithDelta($per, 583.33, 0.01);
        $this->assertEqualsWithDelta(7000 - $per * 11, $last, 0.01);
    }

    public function test_recalculate_marks_everything_paid_on_full_payoff(): void
    {
        $plan = $this->plan();
        $order = $this->order($plan, 1100);
        InstallmentScheduleService::createSchedule($order, $plan);

        InstallmentScheduleService::recordPayment($order, 1100, 'wallet');

        $order = $order->fresh();
        $this->assertEquals(0.0, (float) $order->remaining_amount);
        $this->assertEquals('processing', $order->status);
        $this->assertEquals(4, $order->installmentPayments()->where('status', 'paid')->count());
    }

    public function test_record_payment_marks_target_installment_paid(): void
    {
        $plan = $this->plan();
        $order = $this->order($plan, 1100);
        InstallmentScheduleService::createSchedule($order, $plan);

        $target = $order->installmentPayments()->first();
        InstallmentScheduleService::recordPayment($order, 275, 'gateway', $target);

        $this->assertEquals('paid', $target->fresh()->status);
        $this->assertEquals(275.0, (float) $order->fresh()->paid_amount);
    }

    public function test_late_fee_only_applies_when_enabled_and_overdue(): void
    {
        $plan = $this->plan(['late_fee_enabled' => true, 'late_fee_percent' => 5]);
        $order = $this->order($plan, 1100);
        InstallmentScheduleService::createSchedule($order, $plan);

        // First installment not yet due → no fee.
        $upcoming = $order->installmentPayments()->first();
        $this->assertEquals(0.0, InstallmentScheduleService::lateFeeFor($upcoming));

        // Make it overdue (due date in the past) → 5% fee applies.
        $upcoming->update(['due_date' => now()->subDay()]);
        $this->assertEquals(13.75, InstallmentScheduleService::lateFeeFor($upcoming->fresh()));

        // A plan without late fees enabled never charges one.
        $plain = $this->plan();
        $order2 = $this->order($plain, 1100);
        InstallmentScheduleService::createSchedule($order2, $plain);
        $row = $order2->installmentPayments()->first();
        $row->update(['due_date' => now()->subDay()]);
        $this->assertEquals(0.0, InstallmentScheduleService::lateFeeFor($row->fresh()));
    }

    public function test_next_due_amount_includes_late_fee(): void
    {
        $plan = $this->plan(['late_fee_enabled' => true, 'late_fee_percent' => 10]);
        $order = $this->order($plan, 1100);
        InstallmentScheduleService::createSchedule($order, $plan);

        $order->installmentPayments()->first()->update(['due_date' => now()->subDay()]);

        // 275 + 10% late fee = 302.50
        $this->assertEquals(302.5, InstallmentScheduleService::nextDueAmount($order->fresh()));
    }

    public function test_progress_label_reassures_at_each_stage(): void
    {
        $plan = $this->plan();
        $order = $this->order($plan, 1100);
        InstallmentScheduleService::createSchedule($order, $plan);

        $this->assertStringContainsString('first payment', InstallmentScheduleService::progressLabel($order));

        InstallmentScheduleService::recordPayment($order, 800, 'gateway');
        $this->assertStringContainsString('Almost there', InstallmentScheduleService::progressLabel($order->fresh()));

        InstallmentScheduleService::recordPayment($order, 300, 'gateway');
        $this->assertStringContainsString('all yours', InstallmentScheduleService::progressLabel($order->fresh()));
    }
}
