@extends('backend.layouts.console')
@section('title', 'Edit Plan — '.storeName().' Admin')
@section('page_title', 'Edit Plan')

@section('content')

@if($errors->any())
<div class="mb-4 flex items-start gap-2 rounded-xl border border-ember/25 bg-ember/10 p-4 text-sm text-ember">
    <i class="bi bi-exclamation-circle-fill mt-0.5"></i> {{ $errors->first() }}
</div>
@endif

<div class="os-card p-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-display text-lg font-bold text-ink">{{ $plan->name }}</h2>
            <p class="mt-0.5 text-sm text-slate">Changes apply to new orders — existing schedules are not retroactively rewritten.</p>
        </div>
        <a href="{{ route('admin.plans.index') }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
    </div>

    <form action="{{ route('admin.plans.update', $plan) }}" method="POST" class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @csrf
        <div>
            <label for="name" class="mb-1.5 block text-xs font-semibold text-slate">Plan name <span class="text-ember">*</span></label>
            <input type="text" name="name" id="name" class="os-input w-full" value="{{ old('name', $plan->name) }}" required>
        </div>
        <div>
            <label for="type" class="mb-1.5 block text-xs font-semibold text-slate">Cadence <span class="text-ember">*</span></label>
            <select name="type" id="type" class="os-input w-full" required>
                <option value="weekly" {{ $plan->type === 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="monthly" {{ $plan->type === 'monthly' ? 'selected' : '' }}>Monthly</option>
            </select>
        </div>
        <div>
            <label for="duration" class="mb-1.5 block text-xs font-semibold text-slate">Installments <span class="text-ember">*</span></label>
            <input type="number" name="duration" id="duration" class="os-input w-full" value="{{ old('duration', $plan->duration) }}" min="1" max="52" required>
        </div>
        <div>
            <label for="interest_rate" class="mb-1.5 block text-xs font-semibold text-slate">Interest % <span class="text-ember">*</span></label>
            <input type="number" name="interest_rate" id="interest_rate" class="os-input w-full" value="{{ old('interest_rate', $plan->interest_rate) }}" min="0" max="100" step="0.01" required>
        </div>
        <div class="sm:col-span-2">
            <label for="description" class="mb-1.5 block text-xs font-semibold text-slate">Description</label>
            <input type="text" name="description" id="description" class="os-input w-full" value="{{ old('description', $plan->description) }}">
        </div>
        <div>
            <label for="sort_order" class="mb-1.5 block text-xs font-semibold text-slate">Sort order</label>
            <input type="number" name="sort_order" id="sort_order" class="os-input w-full" value="{{ old('sort_order', $plan->sort_order) }}" min="0">
        </div>
        <div class="flex items-end gap-4 pb-1">
            <label class="flex cursor-pointer items-center gap-2 text-sm text-slate">
                <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded accent-brand" {{ $plan->is_active ? 'checked' : '' }}>
                Active
            </label>
        </div>

        <!-- ===== LATE FEE (optional, off by default) ===== -->
        <div class="sm:col-span-2 lg:col-span-4 mt-2 rounded-xl border border-ink/10 bg-paper p-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 class="font-display text-sm font-bold text-ink">Late fee <span class="os-chip os-chip-ember ml-1 text-[10px]">Optional</span></h3>
                    <p class="mt-0.5 text-xs text-slate">Charged on overdue installments when this plan is used. Off by default.</p>
                </div>
                <label class="flex cursor-pointer items-center gap-2 text-sm font-semibold text-ink">
                    <input type="checkbox" name="late_fee_enabled" value="1" class="h-4 w-4 rounded accent-ember" {{ $plan->late_fee_enabled ? 'checked' : '' }}>
                    Enabled
                </label>
            </div>
            <div class="mt-3 max-w-xs">
                <label for="late_fee_percent" class="mb-1.5 block text-xs font-semibold text-slate">Fee rate (% of installment)</label>
                <input type="number" name="late_fee_percent" id="late_fee_percent" class="os-input w-full" value="{{ old('late_fee_percent', $plan->late_fee_percent) }}" min="0" max="100" step="0.01">
            </div>
        </div>

        <div class="sm:col-span-2 lg:col-span-4 flex justify-end gap-3">
            <a href="{{ route('admin.plans.index') }}" class="os-btn os-btn-ghost">Cancel</a>
            <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Save plan</button>
        </div>
    </form>
</div>

@endsection
