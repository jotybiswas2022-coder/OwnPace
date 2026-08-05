<?php

namespace App\Services\Messaging;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * SmsService — provider-agnostic SMS sending backed by the SMS settings saved
 * on the Secure Config screen (provider, api_key, sender_id, base_url).
 *
 * The default payload shape follows the Termii send-message API, which is the
 * de-facto standard for Nigerian SMS providers and works with most others via
 * their base URL. When no provider is configured yet the message is logged
 * instead of failing — so development works out of the box and production
 * "just works" once credentials are saved.
 */
class SmsService
{
    /**
     * Send a plain SMS. Returns [success, messageId].
     *
     * @return array{0: bool, 1: string|null}
     */
    public static function send(string $phone, string $message, ?string $senderId = null): array
    {
        $phone = self::normalizePhone($phone);

        if ($phone === '' || trim($message) === '') {
            return [false, null];
        }

        $config = MessageConfig::sms();
        $sender = $senderId ?: ($config['sender_id'] ?? 'OwnPace');

        // No provider configured yet — log instead of failing silently.
        if (! MessageConfig::hasSms()) {
            Log::info('[SmsService] no provider configured — logged instead', [
                'to' => $phone,
                'message' => $message,
            ]);

            return [true, 'log-'.now()->format('YmdHis')];
        }

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->asJson()
                ->post($config['base_url'], [
                    'api_key' => $config['api_key'],
                    'to' => $phone,
                    'from' => $sender,
                    'sms' => $message,
                    'type' => 'plain',
                    'channel' => 'generic',
                ]);

            if ($response->successful()) {
                $id = $response->json('message_id') ?? $response->json('data.message_id') ?? null;

                return [true, is_string($id) ? $id : null];
            }

            Log::error('[SmsService] provider rejected message', [
                'to' => $phone,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('[SmsService] send failed', [
                'to' => $phone,
                'error' => $e->getMessage(),
            ]);
        }

        return [false, null];
    }

    /**
     * Strip non-digits except a leading +, and add the default NG prefix to
     * 0-prefixed local numbers (e.g. 0803... -> +234803...).
     */
    public static function normalizePhone(string $phone): string
    {
        $phone = trim($phone);
        $digits = preg_replace('/\D+/', '', $phone);

        if ($digits === null || $digits === '') {
            return '';
        }

        if (str_starts_with($phone, '+')) {
            return '+'.$digits;
        }

        if (str_starts_with($digits, '234')) {
            return '+'.$digits;
        }

        if (str_starts_with($digits, '0')) {
            return '+234'.substr($digits, 1);
        }

        return '+234'.$digits;
    }
}
