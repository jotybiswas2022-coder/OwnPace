@extends('backend.app')
@section('title', 'Customers — OwnPace Admin')
@section('page_title', 'Customers')

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
    <div class="fp-table-header"><h5>All Users</h5></div>
    <table class="fp-table">
        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Orders</th><th>Status</th><th>Role</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($users ?? [] as $u)
            <tr>
                <td data-label="Name"><strong style="color:var(--text-primary);">{{ $u->name ?? 'N/A' }}</strong></td>
                <td data-label="Email" style="color:var(--text-dim);">{{ $u->email }}</td>
                <td data-label="Phone">{{ $u->phone ?? '—' }}</td>
                <td data-label="Orders">{{ $u->orders()->count() }}</td>
                <td data-label="Status"><span class="fp-badge {{ $u->is_suspended ? 'fp-badge-inactive' : 'fp-badge-active' }}">{{ $u->is_suspended ? 'Suspended' : 'Active' }}</span></td>
                <td data-label="Role">{{ $u->is_admin ? 'Admin' : 'User' }}</td>
                <td data-label="Actions">
                    <a href="{{ route('admin.users.show', $u) }}" class="fp-btn fp-btn-ghost" style="padding:4px 10px;"><i class="bi bi-eye"></i> View</a>
                    @if($u->is_suspended)
                    <form action="{{ route('admin.users.unsuspend', $u) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="fp-btn fp-btn-ghost" style="padding:4px 10px;color:#4ade80;"><i class="bi bi-unlock-fill"></i> Unsuspend</button>
                    </form>
                    @else
                    <form action="{{ route('admin.users.suspend', $u) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="fp-btn fp-btn-ghost" style="padding:4px 10px;color:var(--gold-400);"><i class="bi bi-lock-fill"></i> Suspend</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="7" class="text-center py-4" style="color:var(--text-dim);">No users yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection