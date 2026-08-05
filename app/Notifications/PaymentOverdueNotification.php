<?php

namespace App\Notifications;

use App\Models\InstallmentPayment;
use App\Services\Messaging\NotificationChannels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent the day an installment becomes overdue (and never again for the same
 * installment — the reminder command dedupes via notification_logs).
 */
class PaymentOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public InstallmentPayment $payment)
    {
    }

    public function via($notifiable): array
    {
        return NotificationChannels::for('payment_overdue');
    }

    public function toMail($notifiable): MailMessage
    {
        $order = $this->payment->order;
        $lateFee = (float) $this->payment->late_fee;

        $message = (new MailMessage)
            ->subject('Payment overdue on order #'.($order?->order_number ?? $this->payment->order_id).' — '.storeName())
            ->greeting('Hi '.($notifiable->name ?? 'there').',')
            ->line('Your installment of **'.formatPrice($this->payment->amount, 0).'** for order **#'.($order?->order_number ?? $this->payment->order_id).'** was due on **'.$this->payment->due_date->format('M j, Y').'** and is now overdue.')
            ->line('Settle it as soon as possible to keep your plan active.');

        if ($lateFee > 0) {
            $message->line('A late fee of **'.formatPrice($lateFee, 0).'** applies to this installment.');
        }

        return $message
            ->action('Pay now', url('/orders/'.($order?->id ?? '')))
            ->line("We're here to help — reach out if anything got in the way.");
    }

    public function toSms($notifiable): string
    {
        $lateFee = (float) $this->payment->late_fee;

        return storeName().': Your installment of '.formatPrice($this->payment->amount, 0)
            .' for order #'.($this->payment->order?->order_number ?? $this->payment->order_id)
            .' was due '.$this->payment->due_date->format('M j, Y').' and is now overdue.'
            .($lateFee > 0 ? ' A late fee of '.formatPrice($lateFee, 0).' applies.' : '')
            .' Please pay to keep your plan on track.';
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'payment_overdue',
            'title' => 'Payment overdue',
            'message' => 'Your installment of '.formatPrice($this->payment->amount, 0)
                .' for order #'.($this->payment->order?->order_number ?? $this->payment->order_id)
                .' was due '.$this->payment->due_date->format('M j, Y').' and is now overdue.',
        ];
    }
}
