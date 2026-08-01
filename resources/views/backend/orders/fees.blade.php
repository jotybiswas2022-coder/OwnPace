@extends('backend.layouts.console')
@section('title', 'Product Fees — '.storeName().' Admin')
@section('page_title', 'Product Fees')

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

<!-- ===== GLOBAL FEES ===== -->
<div class="os-card p-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-display text-lg font-bold text-ink">Global fees</h2>
            <p class="mt-0.5 text-sm text-slate">Applied storewide unless a product overrides them.</p>
        </div>
        <span class="os-chip">{{ $fees->count() }} configured</span>
    </div>

    <div class="mt-5 overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr>
                    <th>Fee</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th class="w-56">Update</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fees as $fee)
                <tr>
                    <td data-label="Fee">
                        <p class="text-sm font-semibold text-ink">{{ $fee->name }}</p>
                        @if($fee->description)
                            <p class="text-xs text-slate">{{ $fee->description }}</p>
                        @endif
                    </td>
                    <td data-label="Type"><span class="os-chip {{ $fee->type === 'percentage' ? 'os-chip-brand' : '' }}">{{ $fee->type === 'percentage' ? 'Percentage' : 'Fixed' }}</span></td>
                    <td data-label="Amount" class="font-mono text-sm text-ink">
                        {{ $fee->type === 'percentage' ? $fee->amount.'%' : formatPrice($fee->amount, 0) }}
                    </td>
                    <td data-label="Status">
                        @if($fee->is_active)
                            <span class="os-chip os-chip-grass"><i class="bi bi-check-circle-fill"></i> Active</span>
                        @else
                            <span class="os-chip os-chip-ember">Inactive</span>
                        @endif
                    </td>
                    <td data-label="Update">
                        <form action="{{ route('admin.orders.fees.update', $fee) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <input type="number" name="amount" value="{{ $fee->amount }}" step="0.01" min="0" class="os-input w-24 py-1.5 text-sm">
                            <input type="hidden" name="is_active" value="0">
                            <label class="flex cursor-pointer items-center gap-1.5 text-xs text-slate">
                                <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded accent-brand" {{ $fee->is_active ? 'checked' : '' }}>
                                Active
                            </label>
                            <button type="submit" class="os-btn os-btn-brand os-btn-sm">Save</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-6 text-center text-sm text-slate">No fees configured.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-2">
    <!-- ===== ADD OVERRIDE ===== -->
    <div class="os-card p-6">
        <h2 class="font-display text-lg font-bold text-ink">Per-product override</h2>
        <p class="mt-0.5 text-sm text-slate">Set a custom fee for one product, replacing the global value.</p>

        <form action="{{ route('admin.orders.fees.override.store') }}" method="POST" class="mt-5 space-y-4">
            @csrf
            <div>
                <label for="product_id" class="mb-1.5 block text-xs font-semibold text-slate">Product</label>
                <select name="product_id" id="product_id" class="os-input w-full" required>
                    <option value="">Select product…</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ Str::limit($product->name, 60) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="fee_slug" class="mb-1.5 block text-xs font-semibold text-slate">Fee</label>
                    <select name="fee_slug" id="fee_slug" class="os-input w-full" required>
                        @foreach($fees as $fee)
                            <option value="{{ $fee->slug }}">{{ $fee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="type" class="mb-1.5 block text-xs font-semibold text-slate">Type</label>
                    <select name="type" id="type" class="os-input w-full">
                        <option value="fixed">Fixed</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="amount" class="mb-1.5 block text-xs font-semibold text-slate">Amount</label>
                <input type="number" name="amount" id="amount" class="os-input w-full" step="0.01" min="0" placeholder="0.00" required>
            </div>
            <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-plus-lg"></i> Save override</button>
        </form>
    </div>

    <!-- ===== EXISTING OVERRIDES ===== -->
    <div class="os-card p-6">
        <h2 class="font-display text-lg font-bold text-ink">Active overrides</h2>
        <p class="mt-0.5 text-sm text-slate">Products with custom fees.</p>

        <div class="mt-4 divide-y divide-ink/5">
            @forelse($overrides as $override)
            <div class="flex items-center justify-between gap-3 py-3">
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-ink">{{ $override->product?->name ?? 'Deleted product' }}</p>
                    <p class="mt-0.5 text-xs text-slate">
                        {{ $override->fee_slug }} · <span class="font-mono">{{ $override->type === 'percentage' ? $override->amount.'%' : formatPrice($override->amount, 0) }}</span>
                    </p>
                </div>
                <form action="{{ route('admin.orders.fees.override.delete', $override) }}" method="POST" onsubmit="return confirm('Remove this override?')">
                    @csrf
                    <button type="submit" class="os-btn os-btn-ghost os-btn-sm text-ember"><i class="bi bi-trash-fill"></i></button>
                </form>
            </div>
            @empty
            <p class="py-6 text-center text-sm text-slate">No per-product overrides yet.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection
