<?php

namespace App\Services\Messaging;

use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;

/**
 * MessageConfig — single reader for the messaging credentials stored on the
 * settings row by the Secure Config screen. Values are stored as JSON strings
 * (smtp_settings / sms_settings) with secrets prefixed `enc:` (encrypted with
 * the app key at rest). This reader decodes both JSON strings and cast arrays
 * and transparently decrypts `enc:`-prefixed values.
 */
class MessageConfig
{
    /**
     * Read + decode the smtp_settings column into a plain array.
     *
     * @return array{host?: string, port?: string, username?: string, password?: string, encryption?: string, from_address?: string, from_name?: string}
     */
    public static function smtp(): array
    {
        return self::decodeAndDecrypt((string) (Setting::first()?->smtp_settings ?? '{}'), [
            'password' => true,
        ]);
    }

    /**
     * Read + decode the sms_settings column into a plain array.
     *
     * @return array{provider?: string, api_key?: string, sender_id?: string, base_url?: string}
     */
    public static function sms(): array
    {
        return self::decodeAndDecrypt((string) (Setting::first()?->sms_settings ?? '{}'), [
            'api_key' => true,
        ]);
    }

    /**
     * True when a real SMTP server has been configured in the database —
     * used to decide between the store SMTP and the env fallback mailer.
     */
    public static function hasSmtp(): bool
    {
        $smtp = self::smtp();

        return ! empty($smtp['host']) && ! empty($smtp['port']);
    }

    /**
     * True when an SMS provider has been configured in the database.
     */
    public static function hasSms(): bool
    {
        $sms = self::sms();

        return ! empty($sms['api_key']) && ! empty($sms['base_url']);
    }

    /**
     * Decode a JSON-string column value (or pass through an array) and
     * decrypt any `enc:`-prefixed secret keys.
     *
     * @param  array<string, bool>  $secrets  keys whose values may be encrypted
     */
    protected static function decodeAndDecrypt(string $json, array $secrets = []): array
    {
        $raw = $json === '' ? [] : json_decode($json, true);
        $raw = is_array($raw) ? $raw : [];

        foreach ($secrets as $key => $encrypted) {
            $value = $raw[$key] ?? '';
            if (is_string($value) && str_starts_with($value, 'enc:')) {
                try {
                    $raw[$key] = Crypt::decryptString(substr($value, 4));
                } catch (\Throwable $e) {
                    $raw[$key] = '';
                }
            }
        }

        return $raw;
    }
}
