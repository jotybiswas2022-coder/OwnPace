@extends('backend.app')
@section('title', 'User Details — OwnPace Admin')
@section('page_title', 'User: '.($user->name ?? 'N/A'))

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="fp-table-wrap">
            <div class="fp-table-header"><h5>Account Info</h5></div>
            <div style="padding:20px;">
                <p><strong style="color:var(--text-primary);">Name:</strong><br><span style="color:var(--text-muted);">{{ $user->name ?? 'N/A' }}</span></p>
                <p><strong style="color:var(--text-primary);">Email:</strong><br><span style="color:var(--text-muted);">{{ $user->email }}</span></p>
                <p><strong style="color:var(--text-primary);">Phone:</strong><br><span style="color:var(--text-muted);">{{ $user->phone ?? '—' }}</span></p>
                <p><strong style="color:var(--text-primary);">Joined:</strong><br><span style="color:var(--text-muted);">{{ $user->created_at->format('M d, Y') }}</span></p>
                <p><strong style="color:var(--text-primary);">Status:</strong><br>
                    <span class="fp-badge {{ $user->is_suspended ? 'fp-badge-inactive' : 'fp-badge-active' }}">{{ $user->is_suspended ? 'Suspended' : 'Active' }}</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="fp-table-wrap">
            <div class="fp-table-header"><h5>Orders</h5></div>
            <table class="fp-table">
                <thead><tr><th>ID</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    @forelse($user->orders as $o)
                    <tr>
                        <td><strong style="color:var(--text-primary);">#{{ $o->id }}</strong></td>
                        <td style="color:var(--gold-400);font-weight:600;">₦{{ number_format($o->total, 0) }}</td>
                        <td><span class="fp-badge fp-badge-{{ $o->status == 'completed' ? 'active' : 'pending' }}">{{ ucfirst($o->status) }}</span></td>
                        <td style="color:var(--text-dim);">{{ $o->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4" style="color:var(--text-dim);">No orders</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3 d-flex flex-wrap gap-2">
    <form action="{{ route('admin.users.role', $user) }}" method="POST" class="d-inline">
        @csrf
        <select name="role" class="fp-form-control" style="width:auto;display:inline;">
            <option value="user" {{ !$user->is_admin ? 'selected' : '' }}>User</option>
            <option value="admin" {{ $user->is_admin ? 'selected' : '' }}>Admin</option>
        </select>
        <button type="submit" class="fp-btn fp-btn-gold" style="padding:8px 16px;">Set Role</button>
    </form>
    @if($user->is_suspended)
    <form action="{{ route('admin.users.unsuspend', $user) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="fp-btn fp-btn-ghost" style="color:#4ade80;"><i class="bi bi-unlock-fill"></i> Unsuspend</button>
    </form>
    @else
    <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="fp-btn fp-btn-ghost" style="color:var(--gold-400);"><i class="bi bi-lock-fill"></i> Suspend</button>
    </form>
    @endif
    <a href="{{ route('admin.users.delete', $user) }}" class="fp-btn fp-btn-danger" onclick="return confirm('Delete this user permanently?')"><i class="bi bi-trash-fill"></i> Delete</a>
</div>
@endsection