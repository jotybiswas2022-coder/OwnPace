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
     * Insurance fee on an order total at a given percentage (default 10%).
     * Never applied unless the customer opts in — the checkout layer decides.
     */
    public static function insuranceFee(float|int $principal, float|int $ratePercent): float
    {
        return MoneyService::round($principal * $ratePercent / 100);
    }

    /**
     * Optional late fee on an overdue installment at a plan rate (percent).
     * Plans ship with this disabled — it is opt-in per plan.
     */
    public static function lateFee(float|int $amount, float|int $ratePercent): float
    {
        return MoneyService::round($amount * $ratePercent / 100);
    }

    /**
     * Full breakdown array for a plan — the single method the checkout,
     * product and order pages all use.
     *
     * Extends the legacy signature (principal + plan) with the optional
     * shipping fee, insurance rate (applied only when non-null) and promo
     * discount, so every screen shows the same math: price + interest +
     * shipping + insurance − discount = total, total ÷ duration = per payment.
     *
     * @return array{principal: float, interest_rate: float, interest: float, shipping_fee: float, insurance_fee: float, discount: float, total: float, grand_total: float, per_installment: float, duration: int, type: ?string, has_insurance: bool}
     */
    public static function breakdown(
        float|int $principal,
        InstallmentPlan $plan,
        float|int $shipping = 0,
        float|int|null $insuranceRate = null,
        float|int $discount = 0
    ): array {
        $principal = MoneyService::round($principal);
        $shipping = MoneyService::round($shipping);
        $discount = MoneyService::round($discount);
        $hasInsurance = $insuranceRate !== null && $insuranceRate > 0;

        $interest = self::interest($principal, $plan->interest_rate);
        $insurance = $hasInsurance ? self::insuranceFee($principal, (float) $insuranceRate) : 0.0;
        $total = MoneyService::round($principal + $interest + $shipping + $insurance - $discount);
        $per = $plan->duration > 0 ? MoneyService::round($total / $plan->duration) : 0;

        return [
            'principal' => $principal,
            'interest_rate' => (float) $plan->interest_rate,
            'interest' => $interest,
            'shipping_fee' => $shipping,
            'insurance_fee' => $insurance,
            'discount' => $discount,
            'total' => MoneyService::round($principal + $interest),
            'grand_total' => $total,
            'per_installment' => $per,
            'duration' => (int) $plan->duration,
            'type' => $plan->type,
            'has_insurance' => $hasInsurance,
        ];
    }

    /**
     * Breakdown for a pay-once order (no plan): price + shipping + insurance
     * − discount = total. Keeps every checkout number flowing through one place.
     *
     * @return array{principal: float, interest_rate: float, interest: float, shipping_fee: float, insurance_fee: float, discount: float, total: float, grand_total: float, per_installment: float, duration: int, type: string, has_insurance: bool}
     */
    public static function payOnceBreakdown(
        float|int $principal,
        float|int $shipping = 0,
        float|int|null $insuranceRate = null,
        float|int $discount = 0
    ): array {
        $hasInsurance = $insuranceRate !== null && $insuranceRate > 0;
        $insurance = $hasInsurance ? self::insuranceFee($principal, (float) $insuranceRate) : 0.0;
        $total = MoneyService::round($principal + $shipping + $insurance - $discount);

        return [
            'principal' => MoneyService::round($principal),
            'interest_rate' => 0.0,
            'interest' => 0.0,
            'shipping_fee' => MoneyService::round($shipping),
            'insurance_fee' => $insurance,
            'discount' => MoneyService::round($discount),
            'total' => MoneyService::round($principal),
            'grand_total' => $total,
            'per_installment' => $total,
            'duration' => 1,
            'type' => 'full',
            'has_insurance' => $hasInsurance,
        ];
    }

    /**
     * Build an installment schedule array for a plan.
     *
     * Payments are equal with the last one absorbing the rounding remainder,
     * so the schedule always sums EXACTLY to $total (money bugs are expensive).
     * Weekly plans space payments 7 days apart, monthly 30.
     *
     * @return array<int, array{number: int, amount: float, due_date: \Carbon\Carbon}>
     */
    public static function schedule(float|int $total, InstallmentPlan $plan, ?\Carbon\Carbon $start = null): array
    {
        $total = MoneyService::round($total);
        $duration = max(1, (int) $plan->duration);

        // Guard the rounding trap: per × duration must never exceed total,
        // otherwise a tiny total (e.g. ₦1 across 40 weeks) could push the last
        // installment negative. Per-payment rounds DOWN to the whole kobo; the
        // last row absorbs the remainder, so the schedule always sums exactly.
        // Integer cents avoid float drift (round before intdiv).
        $perCents = intdiv((int) round($total * 100), $duration);
        $per = $perCents / 100;

        $cursor = ($start ?? now())->copy();
        $schedule = [];

        for ($i = 1; $i <= $duration; $i++) {
            $cursor = $cursor->addDays($plan->type === 'weekly' ? 7 : 30);
            $amount = $i === $duration
                ? MoneyService::round($total - $per * ($duration - 1))
                : $per;

            $schedule[] = [
                'number' => $i,
                'amount' => $amount,
                'due_date' => $cursor->copy(),
            ];
        }

        return $schedule;
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
