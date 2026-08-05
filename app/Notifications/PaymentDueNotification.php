<?php

namespace App\Notifications;

use App\Models\InstallmentPayment;
use App\Services\Messaging\NotificationChannels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent a few days before an installment comes due, so the customer can plan
 * the payment. Channels (mail / sms / database) are toggled per type from
 * the Secure Config screen — via() resolves the current settings.
 */
class PaymentDueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public InstallmentPayment $payment)
    {
    }

    public function via($notifiable): array
    {
        return NotificationChannels::for('payment_due');
    }

    public function toMail($notifiable): MailMessage
    {
        $order = $this->payment->order;

        return (new MailMessage)
            ->subject('Payment due '.$this->payment->due_date->format('M j') . ' — '.storeName())
            ->greeting('Hi '.($notifiable->name ?? 'there').',')
            ->line('Your installment of **'.formatPrice($this->payment->amount, 0).'** for order **#'.($order?->order_number ?? $this->payment->order_id).'** is due on **'.$this->payment->due_date->format('l, M j, Y').'**.')
            ->line('Keep your plan on track — pay your installment and stay on your path to owning it outright.')
            ->action('Pay now', url('/orders/'.($order?->id ?? '')))
            ->line('Thank you for paying at your own pace with '.storeName().'.');
    }

    public function toSms($notifiable): string
    {
        return storeName().': Reminder — your installment of '.formatPrice($this->payment->amount, 0)
            .' for order #'.($this->payment->order?->order_number ?? $this->payment->order_id)
            .' is due '.$this->payment->due_date->format('M j, Y').'. Pay now to stay on track.';
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'payment_due',
            'title' => 'Payment due '.$this->payment->due_date->format('M j'),
            'message' => 'Your installment of '.formatPrice($this->payment->amount, 0)
                .' for order #'.($this->payment->order?->order_number ?? $this->payment->order_id)
                .' is due on '.$this->payment->due_date->format('M j, Y').'.',
        ];
    }
}
