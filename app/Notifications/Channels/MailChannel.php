<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Services\Messaging\MailerFactory;

/**
 * MailChannel — routes notification emails through the store's DB-configured
 * SMTP (with env fallback). This is what makes Laravel's `mail` channel work
 * without any .env mail credentials.
 */
class MailChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (empty($notifiable->email)) {
            return;
        }

        $message = $notification->toMail($notifiable);

        if (! $message) {
            return;
        }

        // MailMessage::render() produces a full HTML document from the
        // framework's bundled mail views — no mailable required.
        $html = $message->render();
        $subject = $message->subject ?: 'Update from '.storeName();

        MailerFactory::send($notifiable->email, $subject, $html);
    }
}
