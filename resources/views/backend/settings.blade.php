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

@php
    $gc = $settings->gateway_config ?? [];
@endphp

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

    <!-- ===== PAYMENT GATEWAYS ===== -->
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-lg-10">
            <div class="fp-table-wrap">
                <div class="fp-table-header">
                    <h5><i class="bi bi-credit-card"></i> Payment Gateway Configuration</h5>
                    <a href="https://paystack.com" target="_blank" class="fp-btn fp-btn-ghost" style="padding:5px 12px;font-size:11px;">
                        <i class="bi bi-box-arrow-up-right"></i> Get API Keys
                    </a>
                </div>
                <div style="padding:24px;">

                    <!-- Default Gateway Selector -->
                    <div class="mb-4" style="border-bottom:1px solid var(--card-border);padding-bottom:16px;">
                        <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                            <i class="bi bi-star-fill" style="color:var(--gold-500);"></i> Default Payment Gateway
                        </label>
                        <select name="default_gateway" class="fp-form-control" style="max-width:280px;">
                            <option value="paystack" {{ ($settings->default_gateway ?? 'paystack') == 'paystack' ? 'selected' : '' }}>Paystack</option>
                            <option value="flutterwave" {{ ($settings->default_gateway ?? '') == 'flutterwave' ? 'selected' : '' }}>Flutterwave</option>
                            <option value="korapay" {{ ($settings->default_gateway ?? '') == 'korapay' ? 'selected' : '' }}>KoraPay</option>
                        </select>
                        <small style="color:var(--text-dim);font-size:11px;display:block;margin-top:4px;">This gateway will be used as the primary option during checkout.</small>
                    </div>

                    <!-- Paystack -->
                    <div class="mb-4" style="border-bottom:1px solid var(--card-border);padding-bottom:20px;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width:40px;height:40px;border-radius:10px;background:rgba(0,204,127,0.12);display:flex;align-items:center;justify-content:center;font-size:18px;color:#00cc7f;">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </div>
                            <div>
                                <strong style="color:var(--text-primary);font-size:14px;">Paystack</strong>
                                <small style="color:var(--text-dim);font-size:11px;display:block;">paystack.com</small>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label style="display:block;font-size:11px;color:var(--text-dim);margin-bottom:4px;">Public Key</label>
                                <input type="text" name="paystack_public" class="fp-form-control" value="{{ $gc['paystack_public'] ?? '' }}" placeholder="pk_live_xxxxxxxxx">
                            </div>
                            <div class="col-sm-6">
                                <label style="display:block;font-size:11px;color:var(--text-dim);margin-bottom:4px;">Secret Key</label>
                                <input type="password" name="paystack_secret" class="fp-form-control" value="{{ $gc['paystack_secret'] ?? '' }}" placeholder="sk_live_xxxxxxxxx">
                            </div>
                        </div>
                    </div>

                    <!-- Flutterwave -->
                    <div class="mb-4" style="border-bottom:1px solid var(--card-border);padding-bottom:20px;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width:40px;height:40px;border-radius:10px;background:rgba(245,68,90,0.12);display:flex;align-items:center;justify-content:center;font-size:18px;color:#f5445a;">
                                <i class="bi bi-wallet2"></i>
                            </div>
                            <div>
                                <strong style="color:var(--text-primary);font-size:14px;">Flutterwave</strong>
                                <small style="color:var(--text-dim);font-size:11px;display:block;">flutterwave.com</small>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label style="display:block;font-size:11px;color:var(--text-dim);margin-bottom:4px;">Public Key</label>
                                <input type="text" name="flutterwave_public" class="fp-form-control" value="{{ $gc['flutterwave_public'] ?? '' }}" placeholder="FLWPUBK-xxxxxxxxx">
                            </div>
                            <div class="col-sm-6">
                                <label style="display:block;font-size:11px;color:var(--text-dim);margin-bottom:4px;">Secret Key</label>
                                <input type="password" name="flutterwave_secret" class="fp-form-control" value="{{ $gc['flutterwave_secret'] ?? '' }}" placeholder="FLWSECK-xxxxxxxxx">
                            </div>
                            <div class="col-sm-6">
                                <label style="display:block;font-size:11px;color:var(--text-dim);margin-bottom:4px;">Encryption Key</label>
                                <input type="password" name="flutterwave_encryption" class="fp-form-control" value="{{ $gc['flutterwave_encryption'] ?? '' }}" placeholder="FLWSECK-xxxxxxxxx">
                            </div>
                        </div>
                    </div>

                    <!-- KoraPay -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width:40px;height:40px;border-radius:10px;background:rgba(99,102,241,0.12);display:flex;align-items:center;justify-content:center;font-size:18px;color:#6366f1;">
                                <i class="bi bi-shield-fill-check"></i>
                            </div>
                            <div>
                                <strong style="color:var(--text-primary);font-size:14px;">KoraPay</strong>
                                <small style="color:var(--text-dim);font-size:11px;display:block;">korapay.com</small>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label style="display:block;font-size:11px;color:var(--text-dim);margin-bottom:4px;">Public Key</label>
                                <input type="text" name="korapay_public" class="fp-form-control" value="{{ $gc['korapay_public'] ?? '' }}" placeholder="pk_prod_xxxxxxxxx">
                            </div>
                            <div class="col-sm-6">
                                <label style="display:block;font-size:11px;color:var(--text-dim);margin-bottom:4px;">Secret Key</label>
                                <input type="password" name="korapay_secret" class="fp-form-control" value="{{ $gc['korapay_secret'] ?? '' }}" placeholder="sk_prod_xxxxxxxxx">
                            </div>
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