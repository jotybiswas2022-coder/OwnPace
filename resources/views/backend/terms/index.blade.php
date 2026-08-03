@extends('backend.layouts.console')
@section('title', 'Terms & Conditions — '.storeName().' Admin')
@section('page_title', 'Terms & Conditions')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Terms']]])
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

@php
    $types = ['general' => 'General', 'installment' => 'Installment', 'privacy' => 'Privacy', 'refund' => 'Refund', 'payment' => 'Payment', 'delivery' => 'Delivery'];
@endphp

<!-- ===== CREATE ===== -->
<div class="os-card p-6">
    <div>
        <h2 class="font-display text-lg font-bold text-ink">Add terms</h2>
        <p class="mt-0.5 text-sm text-slate">Leave "Applies to plan" empty for global terms shown on every checkout. Scope to a plan to show those terms only when that plan is selected.</p>
    </div>
    <form action="{{ route('admin.terms.store') }}" method="POST" class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @csrf
        <div>
            <label class="mb-1.5 block text-xs font-semibold text-slate">Title <span class="text-ember">*</span></label>
            <input type="text" name="title" class="os-input w-full" value="{{ old('title') }}" placeholder="e.g. Installment Agreement" required>
        </div>
        <div>
            <label class="mb-1.5 block text-xs font-semibold text-slate">Type</label>
            <select name="type" class="os-input w-full">
                @foreach($types as $val => $label)
                    <option value="{{ $val }}" {{ old('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-xs font-semibold text-slate">Applies to plan</label>
            <select name="installment_plan_id" class="os-input w-full">
                <option value="">All plans (global)</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ old('installment_plan_id') == $plan->id ? 'selected' : '' }}>{{ $plan->name }} ({{ $plan->duration }}×)</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end pb-1">
            <label class="flex cursor-pointer items-center gap-2 text-sm text-slate">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded accent-brand" checked>
                Active
            </label>
        </div>
        <div class="sm:col-span-2 lg:col-span-4">
            <label class="mb-1.5 block text-xs font-semibold text-slate">Content <span class="text-ember">*</span></label>
            <textarea name="content" rows="6" class="os-input w-full font-mono text-sm" placeholder="Full terms text…" required>{{ old('content') }}</textarea>
        </div>
        <div class="lg:col-span-4">
            <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-plus-lg"></i> Add terms</button>
        </div>
    </form>
</div>

<!-- ===== EXISTING ===== -->
<div class="os-card mt-6 p-6">
    <h2 class="font-display text-lg font-bold text-ink">All terms documents</h2>
    <p class="mt-0.5 text-sm text-slate">{{ $terms->count() }} document(s).</p>

    <div class="mt-5 space-y-6">
        @forelse($terms as $term)
        <form action="{{ route('admin.terms.update', $term) }}" method="POST" class="rounded-xl border border-ink/10 p-5">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate">Title</label>
                    <input type="text" name="title" class="os-input w-full" value="{{ $term->title }}" required>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate">Type</label>
                    <select name="type" class="os-input w-full">
                        @foreach($types as $val => $label)
                            <option value="{{ $val }}" {{ $term->type === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate">Applies to plan</label>
                    <select name="installment_plan_id" class="os-input w-full">
                        <option value="">All plans (global)</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ $term->installment_plan_id == $plan->id ? 'selected' : '' }}>{{ $plan->name }} ({{ $plan->duration }}×)</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-3 pb-1">
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-slate">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded accent-brand" {{ $term->is_active ? 'checked' : '' }}>
                        Active
                    </label>
                </div>
                <div class="sm:col-span-2 lg:col-span-4">
                    <label class="mb-1.5 block text-xs font-semibold text-slate">Content</label>
                    <textarea name="content" rows="6" class="os-input w-full font-mono text-sm" required>{{ $term->content }}</textarea>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <button type="submit" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-save-fill"></i> Update</button>
                <a href="{{ route('admin.terms.delete', $term) }}" class="os-btn os-btn-ghost os-btn-sm text-ember"
                   onclick="return confirm('Delete these terms?')"><i class="bi bi-trash-fill"></i> Delete</a>
            </div>
        </form>
        @empty
        <p class="py-8 text-center text-sm text-slate">No terms yet — add your first document above.</p>
        @endforelse
    </div>
</div>

@endsection
