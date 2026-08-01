@extends('backend.layouts.console')
@section('title', 'Installment Plans — '.storeName().' Admin')
@section('page_title', 'Installment Plans')

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

<!-- ===== NEW PLAN ===== -->
<div class="os-card p-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-display text-lg font-bold text-ink">Create a plan</h2>
            <p class="mt-0.5 text-sm text-slate">Weekly plans run 4–40 weeks, monthly 1–12 months. Late fees are optional and off by default.</p>
        </div>
    </div>

    <form action="{{ route('admin.plans.store') }}" method="POST" class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @csrf
        <div>
            <label for="name" class="mb-1.5 block text-xs font-semibold text-slate">Plan name <span class="text-ember">*</span></label>
            <input type="text" name="name" id="name" class="os-input w-full" value="{{ old('name') }}" placeholder="e.g. 12 Weeks" required>
        </div>
        <div>
            <label for="type" class="mb-1.5 block text-xs font-semibold text-slate">Cadence <span class="text-ember">*</span></label>
            <select name="type" id="type" class="os-input w-full" required>
                <option value="weekly" {{ old('type') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="monthly" {{ old('type') === 'monthly' ? 'selected' : '' }}>Monthly</option>
            </select>
        </div>
        <div>
            <label for="duration" class="mb-1.5 block text-xs font-semibold text-slate">Installments <span class="text-ember">*</span></label>
            <input type="number" name="duration" id="duration" class="os-input w-full" value="{{ old('duration') }}" min="1" max="52" placeholder="e.g. 12" required>
        </div>
        <div>
            <label for="interest_rate" class="mb-1.5 block text-xs font-semibold text-slate">Interest % <span class="text-ember">*</span></label>
            <input type="number" name="interest_rate" id="interest_rate" class="os-input w-full" value="{{ old('interest_rate', 0) }}" min="0" max="100" step="0.01" placeholder="e.g. 10" required>
        </div>
        <div class="sm:col-span-2">
            <label for="description" class="mb-1.5 block text-xs font-semibold text-slate">Description</label>
            <input type="text" name="description" id="description" class="os-input w-full" value="{{ old('description') }}" placeholder="Short description shown to customers">
        </div>
        <div class="flex items-end gap-4 pb-1">
            <label class="flex cursor-pointer items-center gap-2 text-sm text-slate">
                <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded accent-brand" {{ old('is_active', 1) ? 'checked' : '' }}>
                Active
            </label>
            <label class="flex cursor-pointer items-center gap-2 text-sm text-slate">
                <input type="checkbox" name="late_fee_enabled" value="1" class="h-4 w-4 rounded accent-ember" {{ old('late_fee_enabled') ? 'checked' : '' }}>
                Late fee on
            </label>
            <input type="number" name="late_fee_percent" class="os-input w-24 py-1.5 text-sm" value="{{ old('late_fee_percent', 0) }}" min="0" max="100" step="0.01" placeholder="%">
        </div>
        <div class="flex items-end">
            <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-plus-lg"></i> Create plan</button>
        </div>
    </form>
</div>

<!-- ===== PLANS TABLE ===== -->
<div class="os-card mt-6 p-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-display text-lg font-bold text-ink">All plans</h2>
            <p class="mt-0.5 text-sm text-slate">{{ $plans->count() }} configured across weekly &amp; monthly cadences.</p>
        </div>
    </div>

    <div class="mt-5 overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr>
                    <th>Plan</th>
                    <th>Cadence</th>
                    <th>Installments</th>
                    <th>Interest</th>
                    <th>Late fee</th>
                    <th>Status</th>
                    <th class="w-40">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                <tr>
                    <td data-label="Plan">
                        <p class="text-sm font-semibold text-ink">{{ $plan->name }}</p>
                        @if($plan->description)
                            <p class="text-xs text-slate">{{ Str::limit($plan->description, 40) }}</p>
                        @endif
                    </td>
                    <td data-label="Cadence"><span class="os-chip">{{ $plan->cadence }}</span></td>
                    <td data-label="Installments" class="font-mono text-sm text-ink">{{ $plan->duration }} ×</td>
                    <td data-label="Interest" class="font-mono text-sm text-ink">{{ $plan->interest_rate }}%</td>
                    <td data-label="Late fee">
                        @if($plan->late_fee_enabled)
                            <span class="os-chip os-chip-ember">{{ $plan->late_fee_percent }}%</span>
                        @else
                            <span class="text-xs text-slate">Off</span>
                        @endif
                    </td>
                    <td data-label="Status">
                        @if($plan->is_active)
                            <span class="os-chip os-chip-grass"><i class="bi bi-check-circle-fill"></i> Active</span>
                        @else
                            <span class="os-chip os-chip-ember">Inactive</span>
                        @endif
                    </td>
                    <td data-label="Actions">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.plans.edit', $plan) }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.plans.delete', $plan) }}" method="POST" onsubmit="return confirm('Delete this plan? Orders already on it keep their schedule.')">
                                @csrf
                                <button type="submit" class="os-btn os-btn-ghost os-btn-sm text-ember"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-6 text-center text-sm text-slate">No plans yet — create your first one above.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
