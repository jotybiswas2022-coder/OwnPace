@extends('backend.layouts.console')
@section('title', 'Add Promo Code — '.storeName().' Admin')
@section('page_title', 'Add Promo Code')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Shop', 'route' => 'admin.promo-codes.index'], ['label' => 'Add Promo Code']]])
@endsection

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="os-card overflow-hidden">
        <div class="border-b border-ink/10 px-6 py-4">
            <h3 class="font-display text-sm font-bold text-ink">Promo Code Details</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.promo-codes.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label for="code" class="os-label">Code <span class="text-ember">*</span></label>
                        <input type="text" id="code" name="code" class="os-input w-full font-mono uppercase" placeholder="e.g. SAVE20" required>
                    </div>
                    <div>
                        <label for="type" class="os-label">Type</label>
                        <select id="type" name="type" class="os-input w-full">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>
                    <div>
                        <label for="value" class="os-label">Value <span class="text-ember">*</span></label>
                        <input type="number" step="0.01" id="value" name="value" class="os-input w-full" placeholder="20" required>
                    </div>
                    <div>
                        <label for="min_order_amount" class="os-label">Min Order Amount</label>
                        <input type="number" step="0.01" id="min_order_amount" name="min_order_amount" class="os-input w-full" placeholder="0">
                    </div>
                    <div>
                        <label for="max_uses" class="os-label">Max Uses</label>
                        <input type="number" id="max_uses" name="max_uses" class="os-input w-full" placeholder="Unlimited">
                    </div>
                    <div>
                        <label for="is_active" class="os-label">Active</label>
                        <select id="is_active" name="is_active" class="os-input w-full">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div>
                        <label for="starts_at" class="os-label">Start Date</label>
                        <input type="datetime-local" id="starts_at" name="starts_at" class="os-input w-full">
                    </div>
                    <div>
                        <label for="expires_at" class="os-label">Expiry Date</label>
                        <input type="datetime-local" id="expires_at" name="expires_at" class="os-input w-full">
                    </div>
                </div>
                <div class="flex items-center gap-3 border-t border-ink/10 pt-5">
                    <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Save Promo Code</button>
                    <a href="{{ route('admin.promo-codes.index') }}" class="os-btn os-btn-ghost"><i class="bi bi-arrow-left"></i> Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
