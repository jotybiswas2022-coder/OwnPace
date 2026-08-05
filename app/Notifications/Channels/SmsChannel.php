<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Services\Messaging\SmsService;

/**
 * SmsChannel — sends the SMS form of a notification via SmsService. The
 * notification class implements toSms($notifiable) returning the message text.
 */
class SmsChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (empty($notifiable->phone)) {
            return;
        }

        $text = method_exists($notification, 'toSms') ? $notification->toSms($notifiable) : null;

        if (empty($text) || ! is_string($text)) {
            return;
        }

        SmsService::send($notifiable->phone, $text);
    }
}
