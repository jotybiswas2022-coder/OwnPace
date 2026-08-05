<?php

namespace App\Console\Commands;

use App\Models\InstallmentPayment;
use App\Models\NotificationLog;
use App\Notifications\PaymentOverdueNotification;
use App\Services\InstallmentScheduleService;
use Illuminate\Console\Command;

/**
 * Flips unpaid installments whose due date has passed to `overdue` and
 * notifies the customer once per installment. Runs daily just after midnight,
 * so the overdue badge and the notification land on the same day.
 */
class MarkOverdueInstallments extends Command
{
    protected $signature = 'installments:mark-overdue';

    protected $description = 'Mark past-due installments as overdue and notify customers';

    public function handle(): int
    {
        $payments = InstallmentPayment::with('order.user')
            ->where('status', 'pending')
            ->whereDate('due_date', '<', now()->toDateString())
            ->get();

        $marked = 0;
        $notified = 0;

        foreach ($payments as $payment) {
            // Persist the optional late fee so the notification can quote it.
            $lateFee = InstallmentScheduleService::lateFeeFor($payment);

            $payment->update([
                'status' => 'overdue',
                'late_fee' => $lateFee,
            ]);
            $marked++;

            $user = $payment->order?->user;
            if (! $user) {
                continue;
            }

            $key = [InstallmentPayment::class, (int) $payment->id];
            if (NotificationLog::alreadySent('payment_overdue', ...$key)) {
                continue;
            }

            NotificationLog::create([
                'user_id' => $user->id,
                'type' => 'payment_overdue',
                'entity_type' => $key[0],
                'entity_id' => $key[1],
                'sent_at' => now(),
            ]);

            $user->notify(new PaymentOverdueNotification($payment->fresh()));
            $notified++;
        }

        $this->info("Overdue installments: {$marked} marked, {$notified} notified.");

        return self::SUCCESS;
    }
}
