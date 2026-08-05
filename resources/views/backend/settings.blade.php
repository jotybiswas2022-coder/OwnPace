@extends('backend.layouts.console')
@section('title', 'Settings — '.storeName().' Admin')
@section('page_title', 'Settings')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Settings']]])
@endsection

@section('content')
<form action="{{ url('admin/settings') }}" method="POST" class="mx-auto max-w-4xl space-y-6">
    @csrf

    {{-- ===== GENERAL SETTINGS ===== --}}
    <div class="os-card overflow-hidden">
        <div class="border-b border-ink/10 px-6 py-4">
            <h3 class="font-display text-sm font-bold text-ink"><i class="bi bi-gear-fill text-brand"></i> General Settings</h3>
        </div>
        <div class="grid gap-5 p-6 sm:grid-cols-2">
            <div>
                <label for="email" class="os-label"><i class="bi bi-envelope-fill mr-1 text-mango-deep"></i> Email</label>
                <input type="email" id="email" name="email" class="os-input w-full" value="{{ $settings?->email ?? '' }}" placeholder="Enter email">
            </div>
            <div>
                <label for="phone" class="os-label"><i class="bi bi-telephone-fill mr-1 text-mango-deep"></i> Phone</label>
                <input type="text" id="phone" name="phone" class="os-input w-full" value="{{ $settings?->phone ?? '' }}" placeholder="Enter phone number">
            </div>
            <div>
                <label for="location" class="os-label"><i class="bi bi-geo-alt-fill mr-1 text-mango-deep"></i> Location</label>
                <input type="text" id="location" name="location" class="os-input w-full" value="{{ $settings?->location ?? '' }}" placeholder="Enter location">
            </div>
        </div>
    </div>

    {{-- ===== DEFAULT GATEWAY ===== --}}
    <div class="os-card overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/10 px-6 py-4">
            <h3 class="font-display text-sm font-bold text-ink"><i class="bi bi-credit-card text-brand"></i> Default Payment Gateway</h3>
            <a href="{{ route('admin.secure-config.index') }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-shield-lock-fill"></i> Manage API keys</a>
        </div>
        <div class="p-6">
            <label for="default_gateway" class="os-label"><i class="bi bi-star-fill mr-1 text-mango-deep"></i> Primary Gateway</label>
            <select name="default_gateway" id="default_gateway" class="os-input w-full max-w-xs">
                <option value="paystack" {{ ($settings->default_gateway ?? 'paystack') == 'paystack' ? 'selected' : '' }}>Paystack</option>
                <option value="flutterwave" {{ ($settings->default_gateway ?? '') == 'flutterwave' ? 'selected' : '' }}>Flutterwave</option>
                <option value="korapay" {{ ($settings->default_gateway ?? '') == 'korapay' ? 'selected' : '' }}>KoraPay</option>
            </select>
            <p class="os-help-text">This gateway is offered first at checkout. API keys are managed on the <a href="{{ route('admin.secure-config.index') }}" class="font-semibold text-brand hover:underline">Secure Configuration</a> screen (Super Admin only) and stored encrypted.</p>
        </div>
    </div>

    {{-- ===== WALLET ===== --}}
    <div class="os-card overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/10 px-6 py-4">
            <h3 class="font-display text-sm font-bold text-ink"><i class="bi bi-wallet2 text-brand"></i> Wallet Rules</h3>
            <span class="os-chip {{ ($settings->allow_topup_withdrawal ?? false) ? 'os-chip-grass' : 'os-chip-ember' }}">{{ ($settings->allow_topup_withdrawal ?? false) ? 'Top-ups withdrawable' : 'Top-ups spend-only' }}</span>
        </div>
        <div class="p-6">
            <p class="mb-5 text-sm text-slate">Control how wallet money can move. Cancellation refunds are always store credit (spend-only).</p>
            <div class="grid gap-5 sm:grid-cols-3">
                <div>
                    <label for="allow_topup_withdrawal" class="os-label"><i class="bi bi-arrow-down-up mr-1 text-mango-deep"></i> Top-up Withdrawal</label>
                    <select name="allow_topup_withdrawal" id="allow_topup_withdrawal" class="os-input w-full">
                        <option value="0" {{ !($settings->allow_topup_withdrawal ?? false) ? 'selected' : '' }}>Spend-only (recommended)</option>
                        <option value="1" {{ ($settings->allow_topup_withdrawal ?? false) ? 'selected' : '' }}>Withdrawable (10% fee applies)</option>
                    </select>
                </div>
                <div>
                    <label for="withdrawal_fee_percent" class="os-label"><i class="bi bi-percent mr-1 text-mango-deep"></i> Withdrawal Fee (%)</label>
                    <input type="number" id="withdrawal_fee_percent" name="withdrawal_fee_percent" class="os-input w-full" value="{{ $settings->withdrawal_fee_percent ?? 10 }}" min="0" max="100" step="0.01">
                </div>
                <div>
                    <label for="topup_bonus_percent" class="os-label"><i class="bi bi-gift mr-1 text-mango-deep"></i> Top-up Bonus (%)</label>
                    <input type="number" id="topup_bonus_percent" name="topup_bonus_percent" class="os-input w-full" value="{{ $settings->topup_bonus_percent ?? 0 }}" min="0" max="100" step="0.01">
                </div>
            </div>
        </div>
    </div>

    {{-- ===== INSURANCE ===== --}}
    <div class="os-card overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/10 px-6 py-4">
            <h3 class="font-display text-sm font-bold text-ink"><i class="bi bi-shield-fill-check text-brand"></i> Purchase Insurance</h3>
            <span class="os-chip {{ ($insurance->is_enabled ?? true) ? 'os-chip-grass' : 'os-chip-ember' }}">{{ ($insurance->is_enabled ?? true) ? 'Enabled' : 'Disabled' }}</span>
        </div>
        <div class="p-6">
            <p class="mb-5 text-sm text-slate">Customers can add insurance at checkout (e.g. 10% of the order total) to protect purchases against damage, loss or theft.</p>
            <div class="grid gap-5 sm:grid-cols-3">
                <div>
                    <label for="insurance_enabled" class="os-label"><i class="bi bi-shield-check mr-1 text-mango-deep"></i> Status</label>
                    <select name="insurance_enabled" id="insurance_enabled" class="os-input w-full">
                        <option value="1" {{ ($insurance->is_enabled ?? true) ? 'selected' : '' }}>Enabled</option>
                        <option value="0" {{ !($insurance->is_enabled ?? true) ? 'selected' : '' }}>Disabled</option>
                    </select>
                </div>
                <div>
                    <label for="insurance_rate" class="os-label"><i class="bi bi-percent mr-1 text-mango-deep"></i> Insurance Rate (% of order total)</label>
                    <input type="number" id="insurance_rate" name="insurance_rate" class="os-input w-full" value="{{ $insurance->rate ?? 10 }}" min="0" max="100" step="0.01">
                </div>
            </div>
        </div>
    </div>

    {{-- SAVE --}}
    <div class="flex justify-end">
        <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Save All Settings</button>
    </div>
</form>
@endsection
