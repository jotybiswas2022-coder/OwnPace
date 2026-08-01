@extends('backend.app')
@section('title', 'Edit Promo Code — OwnPace Admin')
@section('page_title', 'Edit Promo Code')

@section('content')
<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>Promo Code Details</h5></div>
    <div style="padding:24px;">
        <form action="{{ route('admin.promo-codes.update', $promoCode) }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-sm-6 col-md-4">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Code</label>
                    <input type="text" name="code" class="fp-form-control" value="{{ $promoCode->code }}" required>
                </div>
                <div class="col-sm-6 col-md-4">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Type</label>
                    <select name="type" class="fp-form-control">
                        <option value="percentage" {{ $promoCode->type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed" {{ $promoCode->type == 'fixed' ? 'selected' : '' }}>Fixed Amount (₦)</option>
                    </select>
                </div>
                <div class="col-sm-6 col-md-4">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Value</label>
                    <input type="number" step="0.01" name="value" class="fp-form-control" value="{{ $promoCode->value }}" required>
                </div>
                <div class="col-sm-6 col-md-4">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Min Order Amount (₦)</label>
                    <input type="number" step="0.01" name="min_order_amount" class="fp-form-control" value="{{ $promoCode->min_order_amount }}">
                </div>
                <div class="col-sm-6 col-md-4">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Max Uses</label>
                    <input type="number" name="max_uses" class="fp-form-control" value="{{ $promoCode->max_uses }}">
                </div>
                <div class="col-sm-6 col-md-4">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Active</label>
                    <select name="is_active" class="fp-form-control">
                        <option value="1" {{ $promoCode->is_active ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !$promoCode->is_active ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Start Date</label>
                    <input type="datetime-local" name="starts_at" class="fp-form-control" value="{{ $promoCode->starts_at?->format('Y-m-d\TH:i') }}">
                </div>
                <div class="col-sm-6">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Expiry Date</label>
                    <input type="datetime-local" name="expires_at" class="fp-form-control" value="{{ $promoCode->expires_at?->format('Y-m-d\TH:i') }}">
                </div>
                <div class="col-12"><button type="submit" class="fp-btn fp-btn-gold"><i class="bi bi-check-lg"></i> Update Promo Code</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
