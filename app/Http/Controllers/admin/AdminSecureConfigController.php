<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Setting;

/**
 * Secure Configuration — Super Admin only.
 *
 * Owns sensitive credentials (payment gateway keys, SMS provider keys, SMTP
 * keys). Secrets are stored encrypted at rest (Crypt::encryptString with an
 * `enc:` prefix) inside the settings JSON columns; public keys stay plain.
 *
 * The general Settings screen no longer edits keys — this is the single
 * source of truth, and the gateway adapters keep working because
 * ReadsGatewayConfig transparently decrypts `enc:`-prefixed values.
 */
class AdminSecureConfigController extends Controller
{
    /** @var array<string,string> gateway config key => human label */
    protected $secretFields = [
        'paystack_secret' => 'Paystack Secret Key',
        'flutterwave_secret' => 'Flutterwave Secret Key',
        'flutterwave_encryption' => 'Flutterwave Encryption Key',
        'korapay_secret' => 'Korapay Secret Key',
    ];

    public function index()
    {
        $settings = Setting::first();

        $gc = $settings?->gateway_config ?? [];
        $sms = is_array($settings?->sms_settings ?? null)
            ? $settings->sms_settings
            : (json_decode((string) ($settings->sms_settings ?? '{}'), true) ?: []);
        $smtp = is_array($settings?->smtp_settings ?? null)
            ? $settings->smtp_settings
            : (json_decode((string) ($settings->smtp_settings ?? '{}'), true) ?: []);

        // Show a masked hint of each stored secret so admins know it's set.
        $masked = [];
        foreach ($this->secretFields as $key => $label) {
            $value = $gc[$key] ?? '';
            $masked[$key] = $value ? ('••••••••'.substr($this->decrypt($value), -4)) : '';
        }

        return view('backend.secure-config', compact('settings', 'gc', 'sms', 'smtp', 'masked'));
    }

    public function update(Request $request)
    {
        $this->authorize('manage', Setting::class);

        $request->validate([
            'default_gateway' => 'nullable|in:paystack,flutterwave,korapay',
            // public keys
            'paystack_public' => 'nullable|string|max:255',
            'flutterwave_public' => 'nullable|string|max:255',
            'korapay_public' => 'nullable|string|max:255',
            // secret keys — empty means "keep existing", 'cleared' checkbox removes
            'paystack_secret' => 'nullable|string|max:255',
            'flutterwave_secret' => 'nullable|string|max:255',
            'flutterwave_encryption' => 'nullable|string|max:255',
            'korapay_secret' => 'nullable|string|max:255',
            'clear_paystack_secret' => 'nullable',
            'clear_flutterwave_secret' => 'nullable',
            'clear_flutterwave_encryption' => 'nullable',
            'clear_korapay_secret' => 'nullable',
            // SMS
            'sms_provider' => 'nullable|string|max:100',
            'sms_api_key' => 'nullable|string|max:255',
            'sms_sender_id' => 'nullable|string|max:50',
            'sms_base_url' => 'nullable|string|max:255',
            'clear_sms_api_key' => 'nullable',
            // SMTP
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|in:tls,ssl,',
            'smtp_from_address' => 'nullable|email|max:255',
            'smtp_from_name' => 'nullable|string|max:255',
            'clear_smtp_password' => 'nullable',
            // Notification channel toggles: ch_<type>_group + ch_<type>_<channel>
            'ch_payment_due_group' => 'nullable',
            'ch_payment_due_mail' => 'nullable',
            'ch_payment_due_sms' => 'nullable',
            'ch_payment_due_database' => 'nullable',
            'ch_payment_overdue_group' => 'nullable',
            'ch_payment_overdue_mail' => 'nullable',
            'ch_payment_overdue_sms' => 'nullable',
            'ch_payment_overdue_database' => 'nullable',
            'ch_order_status_group' => 'nullable',
            'ch_order_status_mail' => 'nullable',
            'ch_order_status_sms' => 'nullable',
            'ch_order_status_database' => 'nullable',
            'ch_delivery_confirmation_group' => 'nullable',
            'ch_delivery_confirmation_mail' => 'nullable',
            'ch_delivery_confirmation_sms' => 'nullable',
            'ch_delivery_confirmation_database' => 'nullable',
        ]);

        $settings = Setting::first() ?? new Setting();

        // ---- Gateway config ----
        $gc = $settings->gateway_config ?? [];
        $gc['paystack_public'] = $request->paystack_public ?? $gc['paystack_public'] ?? '';
        $gc['flutterwave_public'] = $request->flutterwave_public ?? $gc['flutterwave_public'] ?? '';
        $gc['korapay_public'] = $request->korapay_public ?? $gc['korapay_public'] ?? '';

        foreach ($this->secretFields as $key => $label) {
            $clearKey = 'clear_'.$key;
            if ($request->has($clearKey)) {
                $gc[$key] = '';
                continue;
            }
            if (!empty($request->$key)) {
                // Encrypt on write; never store the raw secret.
                $gc[$key] = 'enc:'.Crypt::encryptString($request->$key);
            }
            // empty value + no clear flag => keep existing (masked fields).
        }

        $settings->default_gateway = $request->default_gateway ?? $settings->default_gateway ?? 'paystack';
        $settings->gateway_config = $gc;

        // ---- SMS provider ----
        $sms = $settings->sms_settings ?: [];
        $sms['provider'] = $request->sms_provider ?? $sms['provider'] ?? '';
        $sms['sender_id'] = $request->sms_sender_id ?? $sms['sender_id'] ?? '';
        $sms['base_url'] = $request->sms_base_url ?? $sms['base_url'] ?? '';
        if ($request->has('clear_sms_api_key')) {
            $sms['api_key'] = '';
        } elseif (!empty($request->sms_api_key)) {
            $sms['api_key'] = 'enc:'.Crypt::encryptString($request->sms_api_key);
        }
        // Column is plain text (no cast) — encode explicitly.
        $settings->sms_settings = json_encode($sms);

        // ---- SMTP ----
        $smtp = $settings->smtp_settings ?: [];
        $smtp['host'] = $request->smtp_host ?? $smtp['host'] ?? '';
        $smtp['port'] = $request->smtp_port ?? $smtp['port'] ?? '';
        $smtp['username'] = $request->smtp_username ?? $smtp['username'] ?? '';
        $smtp['encryption'] = $request->smtp_encryption ?? $smtp['encryption'] ?? '';
        $smtp['from_address'] = $request->smtp_from_address ?? $smtp['from_address'] ?? '';
        $smtp['from_name'] = $request->smtp_from_name ?? $smtp['from_name'] ?? '';
        if ($request->has('clear_smtp_password')) {
            $smtp['password'] = '';
        } elseif (!empty($request->smtp_password)) {
            $smtp['password'] = 'enc:'.Crypt::encryptString($request->smtp_password);
        }
        // Column is plain text (no cast) — encode explicitly.
        $settings->smtp_settings = json_encode($smtp);

        // ---- Notification channels (per-type toggles) ----
        $channels = is_array($settings->notification_channels ?? null) ? $settings->notification_channels : [];
        foreach (\App\Services\Messaging\NotificationChannels::TYPES as $type => $label) {
            // Only overwrite a type when its group was submitted.
            if (! $request->has('ch_'.$type.'_group')) {
                continue;
            }

            $checked = [];
            foreach (\App\Services\Messaging\NotificationChannels::CHANNELS as $channel) {
                if ($request->has('ch_'.$type.'_'.$channel)) {
                    $checked[] = $channel;
                }
            }
            $channels[$type] = $checked;
        }
        $settings->notification_channels = $channels !== [] ? $channels : null;

        $settings->save();

        return redirect()->route('admin.secure-config.index')->with('success', 'Secure configuration saved. Secrets are encrypted at rest.');
    }

    protected function decrypt(string $value): string
    {
        if (str_starts_with($value, 'enc:')) {
            try {
                return Crypt::decryptString(substr($value, 4));
            } catch (\Throwable $e) {
                return '';
            }
        }

        return $value;
    }
}
