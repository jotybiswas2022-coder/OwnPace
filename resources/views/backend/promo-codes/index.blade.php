@extends('backend.app')
@section('title', 'Promo Codes — OwnPace Admin')
@section('page_title', 'Promo Codes')

@push('styles')
<style>
@media (max-width: 768px) {
    .fp-table thead { display: none; }
    .fp-table tbody, .fp-table tr, .fp-table td { display: block; }
    .fp-table tr {
        background: var(--card-dark);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-sm);
        padding: 12px;
        margin-bottom: 12px;
    }
    .fp-table td {
        padding: 8px 0;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }
    .fp-table td:last-child { border-bottom: none; }
    .fp-table td:before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--text-dim);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        flex-shrink: 0;
    }
    .fp-table td:last-child:before { display: none; }
    .fp-table td:last-child { justify-content: flex-end; gap: 6px; }
    .fp-table .empty-row td:before { display: none; }
    .fp-table .empty-row td { justify-content: center; }
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <p class="mb-0" style="color:var(--text-muted);">{{ $promoCodes->count() ?? 0 }} codes</p>
    <a href="{{ route('admin.promo-codes.create') }}" class="fp-btn fp-btn-gold"><i class="bi bi-plus-lg"></i> Add Promo Code</a>
</div>
<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>All Promo Codes</h5></div>
    <table class="fp-table">
        <thead><tr><th>Code</th><th>Type</th><th>Value</th><th>Min Order</th><th>Uses</th><th>Valid</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($promoCodes ?? [] as $p)
            <tr>
                <td data-label="Code"><strong style="color:var(--gold-400);font-family:monospace;">{{ $p->code }}</strong></td>
                <td data-label="Type">{{ $p->type == 'percentage' ? '%' : '₦' }}</td>
                <td data-label="Value">{{ $p->type == 'percentage' ? $p->value.'%' : '₦'.number_format($p->value, 0) }}</td>
                <td data-label="Min Order">₦{{ number_format($p->min_order_amount, 0) }}</td>
                <td data-label="Uses">{{ $p->used_count }}{{ $p->max_uses ? '/'.$p->max_uses : '' }}</td>
                <td data-label="Valid">
                    @if($p->starts_at || $p->expires_at)
                        {{ $p->starts_at?->format('M d') }} - {{ $p->expires_at?->format('M d, Y') ?? '∞' }}
                    @else
                        <span style="color:var(--text-dim);">Always</span>
                    @endif
                </td>
                <td data-label="Status"><span class="fp-badge {{ $p->isValid() ? 'fp-badge-active' : 'fp-badge-inactive' }}">{{ $p->isValid() ? 'Active' : 'Inactive' }}</span></td>
                <td data-label="Actions">
                    <a href="{{ route('admin.promo-codes.edit', $p) }}" class="fp-btn fp-btn-ghost" style="padding:4px 10px;"><i class="bi bi-pencil-fill"></i></a>
                    <a href="{{ route('admin.promo-codes.delete', $p) }}" class="fp-btn fp-btn-ghost" style="padding:4px 10px;color:#ef4444;" onclick="return confirm('Delete this promo code?')"><i class="bi bi-trash-fill"></i></a>
                </td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="8" class="text-center py-4" style="color:var(--text-dim);">No promo codes</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
