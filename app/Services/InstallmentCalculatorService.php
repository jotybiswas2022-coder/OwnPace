<?php

namespace App\Services;

use App\Models\InstallmentPlan;

/**
 * InstallmentCalculatorService — every calculation around installment plans:
 * interest, totals, per-installment amounts and payoff progress.
 *
 * All monetary math for the BNPL flow lives here (per the product spec) so it
 * can be unit-tested in isolation. Money bugs are expensive — these methods
 * are deterministic and covered by tests.
 */
class InstallmentCalculatorService
{
    /**
     * Interest amount on a principal at a given annual/plan rate (percent).
     */
    public static function interest(float|int $principal, float|int $ratePercent): float
    {
        return MoneyService::round($principal * $ratePercent / 100);
    }

    /**
     * Full amount payable including interest.
     */
    public static function totalWithInterest(float|int $principal, float|int $ratePercent): float
    {
        return MoneyService::round($principal + self::interest($principal, $ratePercent));
    }

    /**
     * Amount due per installment for a plan of $duration payments.
     */
    public static function perInstallment(
        float|int $principal,
        float|int $ratePercent,
        int $duration
    ): float {
        if ($duration <= 0) {
            return 0;
        }

        return MoneyService::round(self::totalWithInterest($principal, $ratePercent) / $duration);
    }

    /**
     * Full breakdown array for a plan — the single method the checkout,
     * product and order pages all use.
     *
     * @return array{principal: float, interest_rate: float, interest: float, total: float, per_installment: float, duration: int, type: ?string}
     */
    public static function breakdown(float|int $principal, InstallmentPlan $plan): array
    {
        $total = self::totalWithInterest($principal, $plan->interest_rate);
        $per = $plan->duration > 0 ? MoneyService::round($total / $plan->duration) : 0;

        return [
            'principal' => MoneyService::round($principal),
            'interest_rate' => (float) $plan->interest_rate,
            'interest' => self::interest($principal, $plan->interest_rate),
            'total' => $total,
            'per_installment' => $per,
            'duration' => (int) $plan->duration,
            'type' => $plan->type,
        ];
    }

    /**
     * Progress of a plan toward paid-off, as a 0-100 percentage.
     * $paid is what the customer has paid so far; $total the full payable amount.
     */
    public static function progressPercent(float|int $paid, float|int $total): float
    {
        return MoneyService::percentOf($paid, $total);
    }

    /**
     * Remaining balance after $paid toward $total.
     */
    public static function remainingBalance(float|int $paid, float|int $total): float
    {
        return MoneyService::round(max(0, $total - $paid));
    }
}
