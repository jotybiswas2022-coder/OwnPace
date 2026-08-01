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
}
