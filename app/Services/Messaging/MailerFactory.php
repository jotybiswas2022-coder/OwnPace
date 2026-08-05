<?php

namespace App\Services\Messaging;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * MailerFactory — the single place transactional + campaign emails leave the
 * app.
 *
 * Transport priority:
 *   1. The store SMTP server saved on the settings row (Secure Config) —
 *      a dynamic "store_smtp" mailer is registered at runtime from those
 *      credentials, so admins never touch .env to send real mail.
 *   2. The app default mailer from config/mail.php (usually "log" in dev).
 *
 * Emails are always sent with the configured from-address (falls back to the
 * env defaults when no SMTP has been saved yet).
 */
class MailerFactory
{
    /**
     * Send a raw HTML email.
     *
     * @return bool true when the transport accepted the message
     */
    public static function send(string $to, string $subject, string $html, ?string $fromAddress = null, ?string $fromName = null): bool
    {
        $mailer = self::mailerName();
        $from = [
            'address' => $fromAddress ?: self::fromAddress(),
            'name' => $fromName ?: self::fromName(),
        ];

        try {
            Mail::mailer($mailer)->html($html, function (Message $message) use ($to, $subject, $from) {
                $message->to($to)
                    ->subject($subject)
                    ->from($from['address'], $from['name']);
            });

            return true;
        } catch (\Throwable $e) {
            Log::error('MailerFactory send failed', [
                'to' => $to,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Register (or refresh) the dynamic store SMTP mailer from DB settings.
     * When no SMTP has been configured, the env-default mailer is used.
     */
    public static function mailerName(): string
    {
        $smtp = MessageConfig::smtp();

        if (! empty($smtp['host']) && ! empty($smtp['port'])) {
            config([
                'mail.mailers.store_smtp' => [
                    'transport' => 'smtp',
                    'host' => $smtp['host'],
                    'port' => (int) $smtp['port'],
                    'username' => $smtp['username'] ?? null,
                    'password' => $smtp['password'] ?? null,
                    'encryption' => ($smtp['encryption'] ?? '') !== '' ? $smtp['encryption'] : null,
                    'timeout' => null,
                    'local_domain' => parse_url((string) config('app.url', 'http://localhost'), PHP_URL_HOST),
                ],
            ]);

            return 'store_smtp';
        }

        return (string) config('mail.default', 'log');
    }

    public static function fromAddress(): string
    {
        $smtp = MessageConfig::smtp();

        return (string) ($smtp['from_address'] ?? config('mail.from.address', 'hello@example.com'));
    }

    public static function fromName(): string
    {
        $smtp = MessageConfig::smtp();

        return (string) ($smtp['from_name'] ?? config('mail.from.name', 'OwnPace'));
    }
}
