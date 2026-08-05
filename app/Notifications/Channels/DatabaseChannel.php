<?php

namespace App\Notifications\Channels;

use App\Models\Notification;
use Illuminate\Notifications\Notification as LaravelNotification;

/**
 * DatabaseChannel — records an in-app notification row on the app's own
 * `notifications` table (the same table campaigns and admin reminders write
 * to), keeping the customer-facing in-app notification feed in sync.
 */
class DatabaseChannel
{
    public function send(object $notifiable, LaravelNotification $notification): void
    {
        $payload = method_exists($notification, 'toDatabase')
            ? (array) $notification->toDatabase($notifiable)
            : [];

        $type = $payload['type'] ?? 'system';
        $title = $payload['title'] ?? 'Update from '.storeName();
        $message = $payload['message'] ?? '';

        Notification::create([
            'user_id' => $notifiable->getKey(),
            'type' => $type,
            'channel' => 'in_app',
            'title' => $title,
            'message' => $message,
            // 'pending' = unread in the customer's notification feed.
            'status' => 'pending',
        ]);
    }
}
