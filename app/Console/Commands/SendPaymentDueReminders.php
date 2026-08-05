<?php

namespace App\Console\Commands;

use App\Models\InstallmentPayment;
use App\Models\NotificationLog;
use App\Notifications\PaymentDueNotification;
use App\Services\Messaging\NotificationChannels;
use Illuminate\Console\Command;

/**
 * Sends "payment due soon" notifications for installments falling due within
 * the lead window (default 3 days). Deduped per installment via
 * notification_logs, so a customer is never reminded twice about the same
 * payment — and once the payment is made the row is no longer picked up.
 */
class SendPaymentDueReminders extends Command
{
    protected $signature = 'installments:send-reminders {--days=3 : How many days ahead to remind}';

    protected $description = 'Notify customers about installments due within the lead window';

    public function handle(): int
    {
        $days = max(1, (int) $this->option('days'));

        $payments = InstallmentPayment::with('order.user')
            ->where('status', 'pending')
            ->whereDate('due_date', '>=', now()->toDateString())
            ->whereDate('due_date', '<=', now()->addDays($days)->toDateString())
            ->get();

        $sent = 0;
        $skipped = 0;

        foreach ($payments as $payment) {
            $user = $payment->order?->user;

            if (! $user) {
                $skipped++;
                continue;
            }

            $key = [InstallmentPayment::class, (int) $payment->id];
            if (NotificationLog::alreadySent('payment_due', ...$key)) {
                $skipped++;
                continue;
            }

            NotificationLog::create([
                'user_id' => $user->id,
                'type' => 'payment_due',
                'entity_type' => $key[0],
                'entity_id' => $key[1],
                'channels' => NotificationChannels::for('payment_due'),
                'sent_at' => now(),
            ]);

            $user->notify(new PaymentDueNotification($payment));
            $sent++;
        }

        $this->info("Payment-due reminders: {$sent} sent, {$skipped} skipped.");

        return self::SUCCESS;
    }
}
