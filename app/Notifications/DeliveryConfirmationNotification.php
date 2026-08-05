<?php

namespace App\Notifications;

use App\Models\Order;
use App\Services\Messaging\NotificationChannels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Delivery confirmation — sent when an admin marks the order as delivered.
 * The cherry-on-top message: the product is theirs now.
 */
class DeliveryConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    public function via($notifiable): array
    {
        return NotificationChannels::for('delivery_confirmation');
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your order #'.$this->order->order_number.' has been delivered! 🎉')
            ->greeting('Hi '.($notifiable->name ?? 'there').',')
            ->line('Your order **#'.$this->order->order_number.'** has been delivered — it\'s officially in your hands.')
            ->line('Loved it? Leave a review and help fellow customers decide with confidence.')
            ->action('Review your order', url('/orders/'.$this->order->id))
            ->line('Thank you for owning at your own pace with '.storeName().'.');
    }

    public function toSms($notifiable): string
    {
        return storeName().': Order #'.$this->order->order_number.' has been delivered. Enjoy it — it\'s yours! '
            .url('/orders/'.$this->order->id);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'delivery_confirmation',
            'title' => 'Order delivered 🎉',
            'message' => 'Order #'.$this->order->order_number.' has been delivered. Enjoy it — it\'s yours!',
        ];
    }
}
