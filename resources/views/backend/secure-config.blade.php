@extends('backend.layouts.console')
@section('title', 'Secure Configuration — '.storeName().' Admin')
@section('page_title', 'Secure Configuration')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Secure Config']]])
@endsection

@section('content')

@if(session('success'))
<div class="mb-4 flex items-start gap-2 rounded-xl border border-grass/25 bg-grass/10 p-4 text-sm text-grass">
    <i class="bi bi-check-circle-fill mt-0.5"></i> {{ session('success') }}
</div>
@endif
@if($errors->any())
<div class="mb-4 flex items-start gap-2 rounded-xl border border-ember/25 bg-ember/10 p-4 text-sm text-ember">
    <i class="bi bi-exclamation-circle-fill mt-0.5"></i> {{ $errors->first() }}
</div>
@endif

<div class="mb-6 flex items-start gap-3 rounded-xl border border-brand/20 bg-brand/5 p-4 text-sm text-slate">
    <i class="bi bi-shield-lock-fill mt-0.5 text-lg text-brand"></i>
    <div>
        <p class="font-semibold text-ink">Super Admin only · encrypted at rest</p>
        <p class="mt-0.5">Secrets (secret keys, passwords) are encrypted with your application key before being stored. Leave a secret field blank to keep the current value; tick “Clear” to remove it. Public keys are not sensitive and display in plain text.</p>
    </div>
</div>

<form action="{{ route('admin.secure-config.update') }}" method="POST">
    @csrf

    <!-- ===== PAYMENT GATEWAYS ===== -->
    <div class="os-card p-6">
        <h2 class="flex items-center gap-2 font-display text-lg font-bold text-ink"><i class="bi bi-credit-card text-mango-deep"></i> Payment gateways</h2>
        <p class="mt-0.5 text-sm text-slate">The gateway adapters read these keys at charge time. Env fallbacks still apply if a key is empty.</p>

        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate">Default gateway</label>
                <select name="default_gateway" class="os-input w-full">
                    <option value="paystack" {{ ($settings->default_gateway ?? 'paystack') === 'paystack' ? 'selected' : '' }}>Paystack</option>
                    <option value="flutterwave" {{ ($settings->default_gateway ?? '') === 'flutterwave' ? 'selected' : '' }}>Flutterwave</option>
                    <option value="korapay" {{ ($settings->default_gateway ?? '') === 'korapay' ? 'selected' : '' }}>KoraPay</option>
                </select>
            </div>
        </div>

        @php
            $gw = [
                'paystack' => ['label' => 'Paystack', 'public' => 'pk_live_…', 'secret' => 'sk_live_…', 'secret_label' => 'Secret Key'],
                'flutterwave' => ['label' => 'Flutterwave', 'public' => 'FLWPUBK-…', 'secret' => 'FLWSECK-…', 'secret_label' => 'Secret Key'],
                'korapay' => ['label' => 'KoraPay', 'public' => 'pk_prod_…', 'secret' => 'sk_prod_…', 'secret_label' => 'Secret Key'],
            ];
        @endphp
        <div class="mt-5 space-y-5">
            @foreach($gw as $name => $g)
            <div class="rounded-xl border border-ink/10 p-5">
                <h3 class="font-display text-sm font-bold text-ink">{{ $g['label'] }}</h3>
                <div class="mt-3 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate">Public key</label>
                        <input type="text" name="{{ $name }}_public" class="os-input w-full" value="{{ $gc[$name.'_public'] ?? '' }}" placeholder="{{ $g['public'] }}">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate">{{ $g['secret_label'] }}</label>
                        <input type="password" name="{{ $name }}_secret" class="os-input w-full" placeholder="{{ $masked[$name.'_secret'] ?: $g['secret'] }}" autocomplete="new-password">
                        <div class="mt-1.5 flex items-center gap-2">
                            <label class="flex cursor-pointer items-center gap-1.5 text-[11px] text-slate">
                                <input type="checkbox" name="clear_{{ $name }}_secret" value="1" class="h-3.5 w-3.5 accent-ember">
                                Clear saved key
                            </label>
                            @if($masked[$name.'_secret'])
                                <span class="font-mono text-[11px] text-grass"><i class="bi bi-check-circle-fill"></i> Saved</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="rounded-xl border border-ink/10 p-5">
                <h3 class="font-display text-sm font-bold text-ink">Flutterwave encryption key</h3>
                <div class="mt-3 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate">Encryption key</label>
                        <input type="password" name="flutterwave_encryption" class="os-input w-full" placeholder="{{ $masked['flutterwave_encryption'] ?: 'FLWSECK-…' }}" autocomplete="new-password">
                        <div class="mt-1.5 flex items-center gap-2">
                            <label class="flex cursor-pointer items-center gap-1.5 text-[11px] text-slate">
                                <input type="checkbox" name="clear_flutterwave_encryption" value="1" class="h-3.5 w-3.5 accent-ember">
                                Clear saved key
                            </label>
                            @if($masked['flutterwave_encryption'])
                                <span class="font-mono text-[11px] text-grass"><i class="bi bi-check-circle-fill"></i> Saved</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SMS PROVIDER ===== -->
    <div class="os-card mt-6 p-6">
        <h2 class="flex items-center gap-2 font-display text-lg font-bold text-ink"><i class="bi bi-chat-dots-fill text-mango-deep"></i> SMS provider</h2>
        <p class="mt-0.5 text-sm text-slate">Used for transactional SMS (payment reminders, order updates).</p>
        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate">Provider</label>
                <input type="text" name="sms_provider" class="os-input w-full" value="{{ $sms['provider'] ?? '' }}" placeholder="e.g. Termii, Twilio, Africa's Talking">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate">API key / token</label>
                <input type="password" name="sms_api_key" class="os-input w-full" placeholder="{{ !empty($sms['api_key'] ?? '') ? '•••••••• saved' : '…' }}" autocomplete="new-password">
                <div class="mt-1.5 flex items-center gap-2">
                    <label class="flex cursor-pointer items-center gap-1.5 text-[11px] text-slate">
                        <input type="checkbox" name="clear_sms_api_key" value="1" class="h-3.5 w-3.5 accent-ember">
                        Clear saved key
                    </label>
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate">Sender ID</label>
                <input type="text" name="sms_sender_id" class="os-input w-full" value="{{ $sms['sender_id'] ?? '' }}" placeholder="e.g. OwnPace">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate">Base URL (optional)</label>
                <input type="text" name="sms_base_url" class="os-input w-full" value="{{ $sms['base_url'] ?? '' }}" placeholder="https://api.termii.com/api/sms/send">
            </div>
        </div>
    </div>

    <!-- ===== SMTP ===== -->
    <div class="os-card mt-6 p-6">
        <h2 class="flex items-center gap-2 font-display text-lg font-bold text-ink"><i class="bi bi-envelope-at-fill text-mango-deep"></i> SMTP / email</h2>
        <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate">Host</label>
                <input type="text" name="smtp_host" class="os-input w-full" value="{{ $smtp['host'] ?? '' }}" placeholder="smtp.example.com">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate">Port</label>
                <input type="number" name="smtp_port" class="os-input w-full" value="{{ $smtp['port'] ?? '' }}" placeholder="587">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate">Encryption</label>
                <select name="smtp_encryption" class="os-input w-full">
                    <option value="" {{ ($smtp['encryption'] ?? '') === '' ? 'selected' : '' }}>None</option>
                    <option value="tls" {{ ($smtp['encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="ssl" {{ ($smtp['encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate">Username</label>
                <input type="text" name="smtp_username" class="os-input w-full" value="{{ $smtp['username'] ?? '' }}">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate">Password</label>
                <input type="password" name="smtp_password" class="os-input w-full" placeholder="{{ !empty($smtp['password'] ?? '') ? '•••••••• saved' : '' }}" autocomplete="new-password">
                <div class="mt-1.5 flex items-center gap-2">
                    <label class="flex cursor-pointer items-center gap-1.5 text-[11px] text-slate">
                        <input type="checkbox" name="clear_smtp_password" value="1" class="h-3.5 w-3.5 accent-ember">
                        Clear saved password
                    </label>
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate">From address</label>
                <input type="email" name="smtp_from_address" class="os-input w-full" value="{{ $smtp['from_address'] ?? '' }}" placeholder="no-reply@ownpace.com">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate">From name</label>
                <input type="text" name="smtp_from_name" class="os-input w-full" value="{{ $smtp['from_name'] ?? '' }}" placeholder="OwnPace">
            </div>
        </div>
    </div>

    <!-- ===== NOTIFICATION CHANNELS ===== -->
    @php
        $nc = is_array($settings?->notification_channels ?? null) ? $settings->notification_channels : [];
        $ncState = [];
        foreach (\App\Services\Messaging\NotificationChannels::TYPES as $ncType => $ncLabel) {
            $ncState[$ncType] = array_key_exists($ncType, $nc) ? $nc[$ncType] : \App\Services\Messaging\NotificationChannels::CHANNELS;
        }
        $ncChannels = [
            'mail' => ['icon' => 'bi-envelope-fill', 'label' => 'Email'],
            'sms' => ['icon' => 'bi-chat-dots-fill', 'label' => 'SMS'],
            'database' => ['icon' => 'bi-bell-fill', 'label' => 'In-app'],
        ];
    @endphp
    <div class="os-card mt-6 p-6">
        <h2 class="flex items-center gap-2 font-display text-lg font-bold text-ink"><i class="bi bi-broadcast-pin text-mango-deep"></i> Automated notification channels</h2>
        <p class="mt-0.5 text-sm text-slate">Which channels each automated notification uses. Leave every box unchecked to turn a type off entirely.</p>

        <div class="mt-5 space-y-4">
            @foreach (\App\Services\Messaging\NotificationChannels::TYPES as $ncType => $ncLabel)
            <div class="rounded-xl border border-ink/10 p-4">
                <input type="hidden" name="ch_{{ $ncType }}_group" value="1">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h3 class="font-display text-sm font-bold text-ink">{{ $ncLabel }}</h3>
                    <div class="flex flex-wrap gap-4">
                        @foreach ($ncChannels as $ncChannel => $ncMeta)
                        <label class="flex cursor-pointer items-center gap-1.5 text-sm text-slate">
                            <input type="checkbox" name="ch_{{ $ncType }}_{{ $ncChannel }}" value="1"
                                   class="h-4 w-4 accent-mango"
                                   {{ in_array($ncChannel, $ncState[$ncType] ?? [], true) ? 'checked' : '' }}>
                            <i class="bi {{ $ncMeta['icon'] }}"></i> {{ $ncMeta['label'] }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="mt-6">
        <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-shield-lock-fill"></i> Save secure configuration</button>
    </div>
</form>

@endsection
