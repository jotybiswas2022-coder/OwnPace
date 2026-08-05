@extends('backend.layouts.console')
@section('title', 'Account Closures — '.storeName().' Admin')
@section('page_title', 'Account Closure Requests')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Requests', 'route' => 'admin.requests.index'], ['label' => 'Account Closures']]])
@endsection

@section('content')
<div class="os-card overflow-hidden">
    <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
        <div>
            <h3 class="font-display text-sm font-bold text-ink">Account Closure Requests</h3>
            <p class="mt-0.5 text-xs text-slate">{{ $requests->count() ?? 0 }} requests</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr><th>User</th><th>Reason</th><th>Requested</th><th>Status</th><th class="w-72">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($requests ?? [] as $r)
                <tr>
                    <td data-label="User">
                        <p class="font-semibold text-ink">{{ $r->user?->name ?? 'N/A' }}</p>
                        <p class="text-xs text-slate">{{ $r->user?->email }}</p>
                    </td>
                    <td data-label="Reason" class="max-w-xs text-slate">{{ $r->reason ? Str::limit($r->reason, 120) : '—' }}</td>
                    <td data-label="Requested" class="text-xs text-slate">{{ $r->created_at->format('M d, Y') }}</td>
                    <td data-label="Status">
                        <span class="os-chip {{ $r->status == 'approved' ? 'os-chip-ember' : ($r->status == 'rejected' ? 'os-chip-grass' : 'os-chip-mango') }}">{{ ucfirst($r->status) }}</span>
                        @if($r->admin_notes)
                        <p class="mt-1 text-xs text-slate">Note: {{ Str::limit($r->admin_notes, 60) }}</p>
                        @endif
                    </td>
                    <td data-label="Actions">
                        @if($r->status === 'pending')
                        <div class="flex flex-wrap items-center gap-2">
                            <form action="{{ route('admin.requests.deletion-requests.approve', $r) }}" method="POST">
                                @csrf
                                <button type="submit" class="os-btn os-btn-danger os-btn-sm" onclick="return confirm('Deactivate this account?')"><i class="bi bi-person-x"></i> Approve</button>
                            </form>
                            <form action="{{ route('admin.requests.deletion-requests.reject', $r) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <label for="note_{{ $r->id }}" class="sr-only">Note (optional)</label>
                                <input type="text" name="admin_notes" id="note_{{ $r->id }}" placeholder="Note (optional)" class="os-input w-36 py-1.5 text-xs">
                                <button type="submit" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-check-lg"></i> Reject</button>
                            </form>
                        </div>
                        @else
                        <span class="text-xs text-slate">Processed {{ $r->processed_at?->format('M d, Y') }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-14 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-person-x"></i></div>
                        <p class="mt-4 font-semibold text-ink">No account closure requests</p>
                        <p class="mt-1 text-sm text-slate">When customers request account deletion, it will appear here for review.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
