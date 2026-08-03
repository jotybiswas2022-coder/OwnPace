@extends('backend.layouts.console')
@section('title', 'Installment Plans — '.storeName().' Admin')
@section('page_title', 'Installment Plans')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Installment Plans']]])
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
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="font-display text-lg font-bold text-ink">All plans</h2>
            <p class="mt-0.5 text-sm text-slate">{{ $plans->count() }} configured across weekly &amp; monthly cadences.</p>
        </div>
        <form method="GET" action="{{ route('admin.plans.index') }}" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="os-input os-input-sm" placeholder="Search plans…">
            <select name="type" class="os-input os-input-sm">
                <option value="">All cadences</option>
                <option value="weekly" {{ request('type') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="monthly" {{ request('type') === 'monthly' ? 'selected' : '' }}>Monthly</option>
            </select>
            <select name="status" class="os-input os-input-sm">
                <option value="">All statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-funnel-fill"></i> Filter</button>
            @if(request()->has('search') || request()->has('type') || request()->has('status'))
            <a href="{{ route('admin.plans.index') }}" class="os-btn os-btn-ghost os-btn-sm text-ember"><i class="bi bi-x-lg"></i></a>
            @endif
        </form>
    </div>

    <form method="POST" action="{{ route('admin.plans.bulk') }}" id="bulkForm" onsubmit="return confirmBulk()">
        @csrf
        <div class="mt-5 flex flex-wrap items-center gap-2 rounded-lg border border-ink/10 bg-paper-deep/60 px-3 py-2.5">
            <label class="flex cursor-pointer items-center gap-2 text-xs font-semibold text-slate">
                <input type="checkbox" id="selectAll" class="h-4 w-4 rounded accent-brand"> Select all
            </label>
            <span class="mx-1 hidden h-4 w-px bg-ink/10 sm:block"></span>
            <select name="action" id="bulkAction" class="os-input os-input-sm w-40" required>
                <option value="">Bulk action…</option>
                <option value="activate">Activate</option>
                <option value="deactivate">Deactivate</option>
                <option value="delete">Delete</option>
            </select>
            <button type="submit" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-lightning-fill"></i> Apply</button>
            <span class="ml-auto hidden text-xs text-slate sm:block" id="selectedCount">0 selected</span>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="os-table w-full">
                <thead>
                    <tr>
                        <th class="w-10"></th>
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
                        <td><input type="checkbox" name="plan_ids[]" value="{{ $plan->id }}" class="plan-check h-4 w-4 rounded accent-brand"></td>
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
                                {{-- submits the sibling delete form via the form= attribute (no nested forms) --}}
                                <button type="submit" form="deletePlanForm-{{ $plan->id }}" class="os-btn os-btn-ghost os-btn-sm text-ember"
                                        onclick="return confirm('Delete this plan? Orders already on it keep their schedule.')">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="py-6 text-center text-sm text-slate">No plans match your filters — create your first one above.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
</div>

{{-- Per-row delete forms live OUTSIDE the bulk form (nested forms are invalid HTML). --}}
@foreach($plans as $plan)
<form id="deletePlanForm-{{ $plan->id }}" action="{{ route('admin.plans.delete', $plan) }}" method="POST" hidden>
    @csrf
</form>
@endforeach

@endsection

@push('scripts')
<script>
    const boxes = document.querySelectorAll('.plan-check');
    const selectAll = document.getElementById('selectAll');
    const count = document.getElementById('selectedCount');

    function updateCount() {
        const n = document.querySelectorAll('.plan-check:checked').length;
        if (count) count.textContent = n + ' selected';
    }

    selectAll?.addEventListener('change', function () {
        boxes.forEach(b => b.checked = this.checked);
        updateCount();
    });
    boxes.forEach(b => b.addEventListener('change', updateCount));

    window.confirmBulk = function () {
        const action = document.getElementById('bulkAction').value;
        const n = document.querySelectorAll('.plan-check:checked').length;
        if (!n) { alert('Select at least one plan.'); return false; }
        if (action === 'delete') {
            return confirm('Permanently delete ' + n + ' plan(s)? Orders already on them keep their schedules.');
        }
        return true;
    };
</script>
@endpush
