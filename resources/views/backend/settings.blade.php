@extends('backend.app')
@section('title', 'Settings — OwnPace Admin')
@section('page_title', 'Settings')

@section('content')

@if(session('success'))
<div class="fp-table-wrap mb-4" style="border-left:3px solid #4ade80;">
    <div class="p-3" style="color:#4ade80;font-size:14px;">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
    </div>
</div>
@endif

<form action="{{ url('admin/settings') }}" method="POST">
    @csrf

    <!-- ===== GENERAL SETTINGS ===== -->
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-lg-10">
            <div class="fp-table-wrap">
                <div class="fp-table-header"><h5><i class="bi bi-gear-fill"></i> General Settings</h5></div>
                <div style="padding:24px;">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                                <i class="bi bi-envelope-fill" style="color:var(--gold-500);"></i> Email
                            </label>
                            <input type="email" name="email" class="fp-form-control" value="{{ $settings?->email ?? '' }}" placeholder="Enter email">
                        </div>
                        <div class="col-sm-6">
                            <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                                <i class="bi bi-telephone-fill" style="color:var(--gold-500);"></i> Phone
                            </label>
                            <input type="text" name="phone" class="fp-form-control" value="{{ $settings?->phone ?? '' }}" placeholder="Enter phone number">
                        </div>
                        <div class="col-sm-6">
                            <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                                <i class="bi bi-geo-alt-fill" style="color:var(--gold-500);"></i> Location
                            </label>
                            <input type="text" name="location" class="fp-form-control" value="{{ $settings?->location ?? '' }}" placeholder="Enter location">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== DEFAULT GATEWAY ===== -->
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-lg-10">
            <div class="fp-table-wrap">
                <div class="fp-table-header">
                    <h5><i class="bi bi-credit-card"></i> Default Payment Gateway</h5>
                    <a href="{{ route('admin.secure-config.index') }}" class="fp-btn fp-btn-ghost" style="padding:5px 12px;font-size:11px;">
                        <i class="bi bi-shield-lock-fill"></i> Manage API keys (Secure Config)
                    </a>
                </div>
                <div style="padding:24px;">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                        <i class="bi bi-star-fill" style="color:var(--gold-500);"></i> Primary Gateway
                    </label>
                    <select name="default_gateway" class="fp-form-control" style="max-width:280px;">
                        <option value="paystack" {{ ($settings->default_gateway ?? 'paystack') == 'paystack' ? 'selected' : '' }}>Paystack</option>
                        <option value="flutterwave" {{ ($settings->default_gateway ?? '') == 'flutterwave' ? 'selected' : '' }}>Flutterwave</option>
                        <option value="korapay" {{ ($settings->default_gateway ?? '') == 'korapay' ? 'selected' : '' }}>KoraPay</option>
                    </select>
                    <small style="color:var(--text-dim);font-size:11px;display:block;margin-top:8px;">
                        This gateway is offered first at checkout. API keys are managed on the <a href="{{ route('admin.secure-config.index') }}" style="color:var(--gold-500);">Secure Configuration</a> screen (Super Admin only) and are stored encrypted.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== WALLET ===== -->
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-lg-10">
            <div class="fp-table-wrap">
                <div class="fp-table-header">
                    <h5><i class="bi bi-wallet2"></i> Wallet Rules</h5>
                    <span class="fp-badge {{ ($settings->allow_topup_withdrawal ?? false) ? 'fp-badge-active' : 'fp-badge-inactive' }}">{{ ($settings->allow_topup_withdrawal ?? false) ? 'Top-ups withdrawable' : 'Top-ups spend-only' }}</span>
                </div>
                <div style="padding:24px;">
                    <p style="color:var(--text-dim);font-size:13px;margin-bottom:16px;">
                        Control how wallet money can move. Cancellation refunds are always store credit (spend-only).
                    </p>
                    <div class="row g-4 align-items-end">
                        <div class="col-sm-4">
                            <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                                <i class="bi bi-arrow-down-up" style="color:var(--gold-500);"></i> Top-up Withdrawal
                            </label>
                            <select name="allow_topup_withdrawal" class="fp-form-control">
                                <option value="0" {{ !($settings->allow_topup_withdrawal ?? false) ? 'selected' : '' }}>Spend-only (recommended)</option>
                                <option value="1" {{ ($settings->allow_topup_withdrawal ?? false) ? 'selected' : '' }}>Withdrawable (10% fee applies)</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                                <i class="bi bi-percent" style="color:var(--gold-500);"></i> Withdrawal Fee (%)
                            </label>
                            <input type="number" name="withdrawal_fee_percent" class="fp-form-control" value="{{ $settings->withdrawal_fee_percent ?? 10 }}" min="0" max="100" step="0.01">
                        </div>
                        <div class="col-sm-4">
                            <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                                <i class="bi bi-gift" style="color:var(--gold-500);"></i> Top-up Bonus (%)
                            </label>
                            <input type="number" name="topup_bonus_percent" class="fp-form-control" value="{{ $settings->topup_bonus_percent ?? 0 }}" min="0" max="100" step="0.01">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== INSURANCE ===== -->
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-lg-10">
            <div class="fp-table-wrap">
                <div class="fp-table-header">
                    <h5><i class="bi bi-shield-fill-check"></i> Purchase Insurance</h5>
                    <span class="fp-badge {{ ($insurance->is_enabled ?? true) ? 'fp-badge-active' : 'fp-badge-inactive' }}">{{ ($insurance->is_enabled ?? true) ? 'Enabled' : 'Disabled' }}</span>
                </div>
                <div style="padding:24px;">
                    <p style="color:var(--text-dim);font-size:13px;margin-bottom:16px;">
                        Customers can add insurance at checkout (e.g. 10% of the order total) to protect purchases against damage, loss or theft.
                    </p>
                    <div class="row g-4 align-items-end">
                        <div class="col-sm-4">
                            <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                                <i class="bi bi-shield-check" style="color:var(--gold-500);"></i> Status
                            </label>
                            <select name="insurance_enabled" class="fp-form-control">
                                <option value="1" {{ ($insurance->is_enabled ?? true) ? 'selected' : '' }}>Enabled</option>
                                <option value="0" {{ !($insurance->is_enabled ?? true) ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                                <i class="bi bi-percent" style="color:var(--gold-500);"></i> Insurance Rate (% of order total)
                            </label>
                            <input type="number" name="insurance_rate" class="fp-form-control" value="{{ $insurance->rate ?? 10 }}" min="0" max="100" step="0.01">
                        </div>
                        <div class="col-sm-4">
                            <button type="submit" class="fp-btn fp-btn-gold" style="padding:10px 28px;"><i class="bi bi-check-lg"></i> Save All Settings</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SAVE BUTTON -->
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 text-end">
            <button type="submit" class="fp-btn fp-btn-gold" style="padding:10px 28px;"><i class="bi bi-check-lg"></i> Save All Settings</button>
        </div>
    </div>

</form>
@endsection
