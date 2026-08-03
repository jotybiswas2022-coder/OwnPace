@extends('backend.app')
@section('title', 'Account Closures — OwnPace Admin')
@section('page_title', 'Account Closure Requests')

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
    <div class="fp-table-header"><h5>Account Closure Requests</h5></div>
    <table class="fp-table">
        <thead><tr><th>User</th><th>Reason</th><th>Requested</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($requests ?? [] as $r)
            <tr>
                <td data-label="User">
                    <strong style="color:var(--text-primary);">{{ $r->user?->name ?? 'N/A' }}</strong>
                    <small style="display:block;color:var(--text-dim);">{{ $r->user?->email }}</small>
                </td>
                <td data-label="Reason" style="max-width:280px;">{{ $r->reason ? Str::limit($r->reason, 120) : '—' }}</td>
                <td data-label="Requested" style="color:var(--text-dim);font-size:12px;">{{ $r->created_at->format('M d, Y') }}</td>
                <td data-label="Status">
                    <span class="fp-badge {{ $r->status == 'approved' ? 'fp-badge-inactive' : ($r->status == 'rejected' ? 'fp-badge-active' : 'fp-badge-pending') }}">{{ ucfirst($r->status) }}</span>
                    @if($r->admin_notes)
                    <small style="display:block;color:var(--text-dim);margin-top:4px;">Note: {{ Str::limit($r->admin_notes, 60) }}</small>
                    @endif
                </td>
                <td data-label="Actions">
                    @if($r->status === 'pending')
                    <form action="{{ route('admin.requests.deletion-requests.approve', $r) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="fp-btn fp-btn-danger" style="padding:4px 10px;font-size:11px;" onclick="return confirm('Deactivate this account?')">Approve</button>
                    </form>
                    <form action="{{ route('admin.requests.deletion-requests.reject', $r) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="text" name="admin_notes" placeholder="Note (optional)" class="fp-form-control" style="width:150px;display:inline-block;padding:4px 8px;font-size:11px;">
                        <button type="submit" class="fp-btn fp-btn-success" style="padding:4px 10px;font-size:11px;">Reject</button>
                    </form>
                    @else
                    <span style="font-size:12px;color:var(--text-dim);">Processed {{ $r->processed_at?->format('M d, Y') }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="5" class="text-center py-4" style="color:var(--text-dim);">No account closure requests</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
