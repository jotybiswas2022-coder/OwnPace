@extends('backend.layouts.console')
@section('title', 'Promo Codes — '.storeName().' Admin')
@section('page_title', 'Promo Codes')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Shop'], ['label' => 'Promo Codes']]])
@endsection

@section('content')
<div class="os-card overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/10 px-5 py-4">
        <div>
            <h3 class="font-display text-sm font-bold text-ink">All Promo Codes</h3>
            <p class="mt-0.5 text-xs text-slate">{{ $promoCodes->count() ?? 0 }} codes</p>
        </div>
        <a href="{{ route('admin.promo-codes.create') }}" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-plus-lg"></i> Add Promo Code</a>
    </div>
    <div class="overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr><th>Code</th><th>Type</th><th>Value</th><th>Min Order</th><th>Uses</th><th>Valid</th><th>Status</th><th class="w-28">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($promoCodes ?? [] as $p)
                <tr>
                    <td data-label="Code"><span class="font-mono text-sm font-semibold text-mango-ink">{{ $p->code }}</span></td>
                    <td data-label="Type" class="text-slate">{{ $p->type == 'percentage' ? '%' : currency() }}</td>
                    <td data-label="Value" class="text-ink">{{ $p->type == 'percentage' ? $p->value.'%' : formatPrice($p->value, 0) }}</td>
                    <td data-label="Min Order" class="text-slate">{{ formatPrice($p->min_order_amount, 0) }}</td>
                    <td data-label="Uses" class="text-slate">{{ $p->used_count }}{{ $p->max_uses ? '/'.$p->max_uses : '' }}</td>
                    <td data-label="Valid" class="text-xs text-slate">
                        @if($p->starts_at || $p->expires_at)
                            {{ $p->starts_at?->format('M d') }} — {{ $p->expires_at?->format('M d, Y') ?? '∞' }}
                        @else
                            Always
                        @endif
                    </td>
                    <td data-label="Status">
                        <span class="os-chip {{ $p->isValid() ? 'os-chip-grass' : 'os-chip-ember' }}">{{ $p->isValid() ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td data-label="Actions" class="whitespace-nowrap">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.promo-codes.edit', $p) }}" class="os-btn os-btn-ghost os-btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="{{ route('admin.promo-codes.delete', $p) }}" class="os-btn os-btn-danger os-btn-sm" title="Delete" onclick="return confirm('Delete this promo code?')"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-14 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-percent"></i></div>
                        <p class="mt-4 font-semibold text-ink">No promo codes yet</p>
                        <p class="mt-1 text-sm text-slate">Create a code to offer discounts at checkout.</p>
                        <a href="{{ route('admin.promo-codes.create') }}" class="os-btn os-btn-brand os-btn-sm mt-4"><i class="bi bi-plus-lg"></i> Add Promo Code</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
