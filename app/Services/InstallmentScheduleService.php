<?php

namespace App\Services;

use App\Models\InstallmentPayment;
use App\Models\InstallmentPlan;
use App\Models\Order;
use Illuminate\Support\Carbon;

/**
 * InstallmentScheduleService — owns the lifecycle of an order's installment
 * schedule: creating the rows at checkout, recalculating the remaining
 * schedule after ANY payment (early next-installment, partial, or full
 * payoff), and computing optional late fees on overdue payments.
 *
 * Every money decision here is delegated to the tested calculator service.
 */
class InstallmentScheduleService
{
    /**
     * Persist the installment rows for a freshly created installment order.
     */
    public static function createSchedule(Order $order, InstallmentPlan $plan): void
    {
        foreach (InstallmentCalculatorService::schedule($order->grand_total, $plan) as $row) {
            InstallmentPayment::create([
                'order_id' => $order->id,
                'installment_number' => $row['number'],
                'amount' => $row['amount'],
                'due_date' => $row['due_date'],
                'status' => 'pending',
                'paid_amount' => 0,
            ]);
        }
    }

    /**
     * The first unpaid installment, in schedule order.
     */
    public static function nextUnpaid(Order $order): ?InstallmentPayment
    {
        return $order->installmentPayments()
            ->where('status', '!=', 'paid')
            ->orderBy('installment_number')
            ->first();
    }

    /**
     * Amount due to clear the next unpaid installment, including any optional
     * late fee if that payment is overdue and the plan has late fees enabled.
     */
    public static function nextDueAmount(Order $order): float
    {
        $next = self::nextUnpaid($order);

        if (!$next) {
            return 0;
        }

        return MoneyService::round($next->amount + self::lateFeeFor($next));
    }

    /**
     * Optional late fee on an overdue installment, from the order's plan.
     * Off by default — returns 0 unless the plan enables it.
     */
    public static function lateFeeFor(InstallmentPayment $payment): float
    {
        $plan = $payment->order?->installmentPlan;

        if (!$plan || !$plan->late_fee_enabled || (float) $plan->late_fee_percent <= 0) {
            return 0;
        }

        if ($payment->status === 'paid' || !$payment->due_date || !$payment->due_date->isPast()) {
            return 0;
        }

        return InstallmentCalculatorService::lateFee($payment->amount, $plan->late_fee_percent);
    }

    /**
     * Mark a payment as paid and keep the rest of the schedule consistent.
     *
     * - The installment is marked paid (with optional late fee recorded).
     * - The order's paid/remaining amounts are advanced by $amount.
     * - The remaining schedule is redistributed so unpaid installments always
     *   sum exactly to the remaining balance (last row absorbs the remainder).
     */
    public static function recordPayment(Order $order, float $amount, string $method = 'gateway', ?InstallmentPayment $target = null): void
    {
        $amount = MoneyService::round($amount);

        if ($target && $target->order_id === $order->id && $target->status !== 'paid') {
            $lateFee = self::lateFeeFor($target);
            $target->update([
                'status' => 'paid',
                'paid_date' => now(),
                'paid_amount' => MoneyService::round($target->amount + $lateFee),
                'late_fee' => $lateFee,
                'payment_method' => $method,
            ]);
        }

        $paid = MoneyService::round($order->paid_amount + $amount);
        $remaining = MoneyService::round(max(0, $order->grand_total - $paid));

        $order->update([
            'paid_amount' => $paid,
            'remaining_amount' => $remaining,
            'status' => $remaining <= 0 ? 'processing' : 'partial_paid',
        ]);

        self::recalculate($order);
    }

    /**
     * Recalculate the remaining schedule after any payment.
     *
     * The invariant: the sum of all UNPAID installment amounts must equal the
     * order's remaining balance. If the order is fully paid, every unpaid row
     * is closed out. Otherwise the remaining balance is split evenly across
     * the remaining rows, with the last one absorbing the rounding remainder.
     */
    public static function recalculate(Order $order): void
    {
        $grandTotal = (float) $order->grand_total;
        $paid = (float) $order->paid_amount;
        $remaining = MoneyService::round(max(0, $grandTotal - $paid));

        $unpaid = $order->installmentPayments()
            ->where('status', '!=', 'paid')
            ->orderBy('installment_number')
            ->get();

        if ($unpaid->isEmpty()) {
            return;
        }

        // Fully paid — close out every remaining row.
        if ($remaining <= 0) {
            foreach ($unpaid as $row) {
                $row->update([
                    'status' => 'paid',
                    'paid_date' => now(),
                    'paid_amount' => (float) $row->amount,
                ]);
            }

            return;
        }

        // Distribute the remaining balance across the remaining rows.
        $count = $unpaid->count();
        $per = MoneyService::round($remaining / $count);

        foreach ($unpaid as $index => $row) {
            $amount = $index === $count - 1
                ? MoneyService::round($remaining - $per * ($count - 1))
                : $per;

            $row->update(['amount' => $amount]);
        }
    }

    /**
     * Human label for how far along an order is, used for reassuring copy.
     */
    public static function progressLabel(Order $order): string
    {
        $pct = InstallmentCalculatorService::progressPercent($order->paid_amount, $order->grand_total);

        if ($pct >= 100) {
            return "Fully paid — it's all yours!";
        }

        if ($pct >= 70) {
            return 'Almost there — just ' . MoneyService::plain($order->remaining_amount) . ' left to own it.';
        }

        if ($pct > 0) {
            return 'Keep going — every payment brings you closer to owning it.';
        }

        return 'Your journey to ownership starts with your first payment.';
    }
}
