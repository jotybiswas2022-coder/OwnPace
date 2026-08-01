@extends('backend.app')
@section('title', 'Orders — OwnPace Admin')
@section('page_title', 'Orders')

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
<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>All Orders</h5></div>
    <table class="fp-table">
        <thead><tr><th>ID</th><th>Customer</th><th>Amount</th><th>Plan</th><th>Status</th><th>Delivery</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($orders ?? [] as $o)
            <tr>
                <td data-label="ID"><strong style="color:var(--text-primary);">#{{ $o->id }}</strong></td>
                <td data-label="Customer">{{ $o->user?->name ?? 'N/A' }}</td>
                <td data-label="Amount" style="color:var(--gold-400);font-weight:600;">₦{{ number_format($o->total, 0) }}</td>
                <td data-label="Plan">{{ $o->installmentPlan?->duration ?? 'N/A' }} {{ $o->installmentPlan?->duration_unit ?? '' }}</td>
                <td data-label="Status"><span class="fp-badge fp-badge-{{ $o->status == 'completed' ? 'active' : ($o->status == 'cancelled' ? 'inactive' : 'pending') }}">{{ ucfirst($o->status) }}</span></td>
                <td data-label="Delivery">{{ ucfirst($o->delivery_status ?? 'pending') }}</td>
                <td data-label="Date" style="color:var(--text-dim);font-size:12px;">{{ $o->created_at->format('M d, Y') }}</td>
                <td data-label="Actions"><a href="{{ route('admin.orders.show', $o) }}" class="fp-btn fp-btn-ghost" style="padding:4px 10px;"><i class="bi bi-eye"></i></a></td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="8" class="text-center py-4" style="color:var(--text-dim);">No orders yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3"><a href="{{ route('admin.orders.export') }}" class="fp-btn fp-btn-ghost"><i class="bi bi-download"></i> Export CSV</a></div>
@endsection