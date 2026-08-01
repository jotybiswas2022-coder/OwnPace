<?php

namespace Tests\Unit\Services;

use App\Models\InstallmentPlan;
use App\Services\InstallmentCalculatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstallmentCalculatorServiceTest extends TestCase
{
    use RefreshDatabase;

    private function plan(float $rate = 10.0, int $duration = 4, string $type = 'weekly'): InstallmentPlan
    {
        return new InstallmentPlan([
            'interest_rate' => $rate,
            'duration' => $duration,
            'type' => $type,
        ]);
    }

    public function test_interest_is_principal_times_rate(): void
    {
        $this->assertEquals(100.0, InstallmentCalculatorService::interest(1000, 10));
        $this->assertEquals(50.0, InstallmentCalculatorService::interest(1000, 5));
    }

    public function test_total_with_interest(): void
    {
        $this->assertEquals(1100.0, InstallmentCalculatorService::totalWithInterest(1000, 10));
    }

    public function test_per_installment_splits_total_evenly(): void
    {
        // 1000 + 10% = 1100 across 4 installments = 275 each
        $this->assertEquals(275.0, InstallmentCalculatorService::perInstallment(1000, 10, 4));
    }

    public function test_per_installment_guards_against_zero_duration(): void
    {
        $this->assertEquals(0.0, InstallmentCalculatorService::perInstallment(1000, 10, 0));
    }

    public function test_breakdown_returns_all_fields(): void
    {
        $breakdown = InstallmentCalculatorService::breakdown(1000, $this->plan(10, 4, 'weekly'));

        $this->assertEquals(1000.0, $breakdown['principal']);
        $this->assertEquals(10.0, $breakdown['interest_rate']);
        $this->assertEquals(100.0, $breakdown['interest']);
        $this->assertEquals(1100.0, $breakdown['total']);
        $this->assertEquals(275.0, $breakdown['per_installment']);
        $this->assertEquals(4, $breakdown['duration']);
        $this->assertEquals('weekly', $breakdown['type']);
    }

    public function test_progress_percent(): void
    {
        $this->assertEquals(60.0, InstallmentCalculatorService::progressPercent(660, 1100));
        $this->assertEquals(0.0, InstallmentCalculatorService::progressPercent(0, 1100));
        $this->assertEquals(100.0, InstallmentCalculatorService::progressPercent(1100, 1100));
    }

    public function test_remaining_balance(): void
    {
        $this->assertEquals(440.0, InstallmentCalculatorService::remainingBalance(660, 1100));
        $this->assertEquals(0.0, InstallmentCalculatorService::remainingBalance(1200, 1100));
    }

    // ===== INSURANCE =====

    public function test_insurance_fee_is_rate_percent_of_principal(): void
    {
        $this->assertEquals(100.0, InstallmentCalculatorService::insuranceFee(1000, 10));
        $this->assertEquals(50.0, InstallmentCalculatorService::insuranceFee(1000, 5));
    }

    // ===== FULL BREAKDOWN (shipping + insurance + discount) =====

    public function test_breakdown_shortest_weekly_plan_without_insurance(): void
    {
        // 4 weekly installments, 5% interest, ₦2,000 shipping, no insurance.
        $plan = $this->plan(5, 4, 'weekly');
        $b = InstallmentCalculatorService::breakdown(1000, $plan, 2000, null);

        $this->assertEquals(1000.0, $b['principal']);
        $this->assertEquals(50.0, $b['interest']);
        $this->assertEquals(2000.0, $b['shipping_fee']);
        $this->assertEquals(0.0, $b['insurance_fee']);
        $this->assertFalse($b['has_insurance']);
        $this->assertEquals(3050.0, $b['grand_total']);
        $this->assertEquals(762.5, $b['per_installment']);
    }

    public function test_breakdown_longest_weekly_plan_with_insurance(): void
    {
        // 40 weekly installments, 15% interest, shipping + 10% insurance.
        $plan = $this->plan(15, 40, 'weekly');
        $b = InstallmentCalculatorService::breakdown(1000, $plan, 2000, 10);

        $this->assertEquals(150.0, $b['interest']);
        $this->assertEquals(100.0, $b['insurance_fee']);
        $this->assertTrue($b['has_insurance']);
        $this->assertEquals(3250.0, $b['grand_total']);
        $this->assertEquals(81.25, $b['per_installment']);
        $this->assertEquals(40, $b['duration']);
    }

    public function test_breakdown_shortest_monthly_plan_with_insurance(): void
    {
        // 1 monthly installment, 8% interest, insurance on.
        $plan = $this->plan(8, 1, 'monthly');
        $b = InstallmentCalculatorService::breakdown(5000, $plan, 1000, 10);

        $this->assertEquals(400.0, $b['interest']);
        $this->assertEquals(500.0, $b['insurance_fee']);
        $this->assertEquals(6900.0, $b['grand_total']);
        $this->assertEquals(6900.0, $b['per_installment']);
    }

    public function test_breakdown_longest_monthly_plan_without_insurance(): void
    {
        // 12 monthly installments, 22% interest, no insurance.
        $plan = $this->plan(22, 12, 'monthly');
        $b = InstallmentCalculatorService::breakdown(5000, $plan, 1000, null);

        $this->assertEquals(1100.0, $b['interest']);
        $this->assertEquals(0.0, $b['insurance_fee']);
        $this->assertEquals(7100.0, $b['grand_total']);
        $this->assertEquals(591.67, $b['per_installment']); // 7100 / 12, rounded
    }

    public function test_breakdown_applies_discount_before_total(): void
    {
        $plan = $this->plan(10, 4, 'weekly');
        $b = InstallmentCalculatorService::breakdown(1000, $plan, 500, null, 200);

        // 1000 + 100 interest + 500 shipping − 200 discount = 1400
        $this->assertEquals(1400.0, $b['grand_total']);
        $this->assertEquals(200.0, $b['discount']);
        $this->assertEquals(350.0, $b['per_installment']);
    }

    public function test_breakdown_legacy_signature_still_works(): void
    {
        // The product page calls breakdown(principal, plan) — keep it stable.
        $b = InstallmentCalculatorService::breakdown(1000, $this->plan(10, 4));
        $this->assertEquals(1100.0, $b['grand_total']);
        $this->assertEquals(275.0, $b['per_installment']);
        $this->assertEquals(0.0, $b['shipping_fee']);
    }

    // ===== SCHEDULE =====

    public function test_schedule_sums_exactly_to_total(): void
    {
        $plan = $this->plan(10, 4, 'weekly');
        $schedule = InstallmentCalculatorService::schedule(1100, $plan);

        $sum = collect($schedule)->sum('amount');
        $this->assertCount(4, $schedule);
        $this->assertEquals(1100.0, $sum);
        // Equal split with no remainder here.
        $this->assertEquals(275.0, $schedule[0]['amount']);
    }

    public function test_schedule_last_installment_absorbs_rounding_remainder(): void
    {
        // 7100 across 12 — per-payment floors to the whole kobo (591.66) and
        // the last row absorbs the remainder (591.74), summing exactly to 7100.
        $plan = $this->plan(22, 12, 'monthly');
        $schedule = InstallmentCalculatorService::schedule(7100, $plan);

        $sum = collect($schedule)->sum('amount');
        $this->assertEqualsWithDelta(7100.0, $sum, 0.001);
        $this->assertEquals(591.66, $schedule[0]['amount']);
        $this->assertEquals(591.74, $schedule[11]['amount']); // 7100 − 591.66 × 11
    }

    public function test_schedule_weekly_spaces_seven_days(): void
    {
        $plan = $this->plan(0, 4, 'weekly');
        $schedule = InstallmentCalculatorService::schedule(400, $plan, now()->startOfDay());

        $this->assertEquals(7, $schedule[0]['due_date']->diffInDays($schedule[1]['due_date']));
    }

    // ===== LATE FEE =====

    public function test_late_fee_is_percent_of_installment(): void
    {
        $this->assertEquals(10.0, InstallmentCalculatorService::lateFee(100, 10));
        $this->assertEquals(0.0, InstallmentCalculatorService::lateFee(100, 0));
    }

    // ===== PAY-ONCE =====

    public function test_pay_once_breakdown_matches_full_payment_math(): void
    {
        $b = InstallmentCalculatorService::payOnceBreakdown(5000, 1000, 10, 500);

        $this->assertEquals(5000.0, $b['principal']);
        $this->assertEquals(0.0, $b['interest']);
        $this->assertEquals(1000.0, $b['shipping_fee']);
        $this->assertEquals(500.0, $b['insurance_fee']);
        $this->assertEquals(500.0, $b['discount']);
        $this->assertEquals(6000.0, $b['grand_total']); // 5000+1000+500-500
        $this->assertEquals(6000.0, $b['per_installment']);
        $this->assertEquals('full', $b['type']);
    }

    // ===== ROUNDING GUARD =====

    public function test_schedule_never_goes_negative_for_tiny_totals(): void
    {
        // The infamous edge: ₦1 across 40 installments must not produce a
        // negative last row from per-payment rounding up.
        $plan = $this->plan(0, 40, 'weekly');
        $schedule = InstallmentCalculatorService::schedule(1, $plan);

        $this->assertCount(40, $schedule);
        foreach ($schedule as $row) {
            $this->assertGreaterThanOrEqual(0, $row['amount']);
        }
        $this->assertEqualsWithDelta(1.0, collect($schedule)->sum('amount'), 0.001);
        // per = 0.02 (2.5 kobo floors to 2), last absorbs the 0.22 remainder.
        $this->assertEquals(0.02, $schedule[0]['amount']);
        $this->assertEquals(0.22, $schedule[39]['amount']);
    }
}
