@extends('backend.app')
@section('title', 'Plan Changes — OwnPace Admin')
@section('page_title', 'Plan Change Requests')

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
    <div class="fp-table-header"><h5>Plan Change Requests</h5></div>
    <table class="fp-table">
        <thead><tr><th>User</th><th>Order</th><th>Current Plan</th><th>Reason</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($requests ?? [] as $r)
            <tr>
                <td data-label="User"><strong style="color:var(--text-primary);">{{ $r->user?->name ?? 'N/A' }}</strong></td>
                <td data-label="Order">#{{ $r->order_id }}</td>
                <td data-label="Current Plan">{{ $r->current_plan ?? 'N/A' }}</td>
                <td data-label="Reason" style="max-width:200px;">{{ Str::limit($r->reason, 50) }}</td>
                <td data-label="Status"><span class="fp-badge {{ $r->status == 'approved' ? 'fp-badge-active' : ($r->status == 'rejected' ? 'fp-badge-inactive' : 'fp-badge-pending') }}">{{ ucfirst($r->status) }}</span></td>
                <td data-label="Actions">
                    <form action="{{ route('admin.requests.plan-changes.approve', $r) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="fp-btn fp-btn-gold" style="padding:4px 10px;font-size:11px;">Approve</button>
                    </form>
                    <form action="{{ route('admin.requests.plan-changes.reject', $r) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="fp-btn fp-btn-danger" style="padding:4px 10px;font-size:11px;">Reject</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="6" class="text-center py-4" style="color:var(--text-dim);">No requests</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection