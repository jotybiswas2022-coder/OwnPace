<?php

namespace App\Notifications;

use App\Models\Order;
use App\Services\Messaging\NotificationChannels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent when an order moves to a new state (Processing, Shipped, In transit,
 * Completed, ...). Fired from the admin order status update and the delivery
 * transition hook.
 */
class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order, public string $status)
    {
    }

    public function via($notifiable): array
    {
        return NotificationChannels::for('order_status');
    }

    public function toMail($notifiable): MailMessage
    {
        $label = self::statusLabel($this->status);

        return (new MailMessage)
            ->subject('Order #'.$this->order->order_number.' is now '.$label.' — '.storeName())
            ->greeting('Hi '.($notifiable->name ?? 'there').',')
            ->line('Good news! Order **#'.$this->order->order_number.'** is now **'.$label.'**.')
            ->line('You can follow every step of your order on the tracking page.')
            ->action('Track order', url('/orders/'.$this->order->id.'/tracking'))
            ->line('Thanks for owning at your own pace with '.storeName().'.');
    }

    public function toSms($notifiable): string
    {
        return storeName().': Order #'.$this->order->order_number
            .' is now '.self::statusLabel($this->status)
            .'. Track it here: '.url('/orders/'.$this->order->id.'/tracking');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'order_status',
            'title' => 'Order '.self::statusLabel($this->status),
            'message' => 'Order #'.$this->order->order_number.' is now '.self::statusLabel($this->status).'.',
        ];
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'processing' => 'Processing',
            'partial_paid' => 'Partially paid',
            'completed', 'fully_paid' => 'Completed',
            'shipped' => 'Shipped',
            'in_transit' => 'In transit',
            'out_for_delivery' => 'Out for delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            default => ucwords(str_replace('_', ' ', $status)),
        };
    }
}
