@extends('backend.app')
@section('title', 'Campaigns — OwnPace Admin')
@section('page_title', 'Campaigns')

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
@if(session('success'))
<div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);border-radius:8px;padding:12px 16px;margin-bottom:20px;color:#4ade80;font-size:13px;display:flex;align-items:center;gap:8px;">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:12px 16px;margin-bottom:20px;color:#ef4444;font-size:13px;display:flex;align-items:center;gap:8px;">
    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
</div>
@endif

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <p class="mb-0" style="color:var(--text-muted);">{{ $campaigns->total() }} campaigns</p>
    <a href="{{ route('admin.campaigns.create') }}" class="fp-btn fp-btn-gold"><i class="bi bi-plus-lg"></i> New Campaign</a>
</div>

<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>All Campaigns</h5></div>
    <table class="fp-table">
        <thead>
            <tr>
                <th>Campaign</th>
                <th>Channel</th>
                <th>Audience</th>
                <th>Sent</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($campaigns ?? [] as $c)
             <tr>
                <td data-label="Campaign">
                    <strong style="color:var(--text-primary);font-size:14px;">{{ $c->name }}</strong>
                    @if($c->subject)
                    <div style="font-size:11px;color:var(--text-dim);margin-top:2px;">{{ Str::limit($c->subject, 40) }}</div>
                    @endif
                </td>
                <td data-label="Channel">
                    @if($c->channel === 'both')
                        <span style="color:var(--gold-400);font-size:12px;"><i class="bi bi-envelope-fill"></i> + <i class="bi bi-chat-dots-fill"></i> Both</span>
                    @elseif($c->channel === 'email')
                        <span style="font-size:12px;"><i class="bi bi-envelope-fill"></i> Email</span>
                    @else
                        <span style="font-size:12px;"><i class="bi bi-chat-dots-fill"></i> SMS</span>
                    @endif
                </td>
                <td data-label="Audience" style="font-size:13px;color:var(--text-muted);">{{ ucwords(str_replace('_', ' ', $c->audience)) }}</td>
                <td data-label="Sent">
                    <span style="font-weight:600;color:var(--text-primary);font-size:13px;">{{ $c->logs_count ?? 0 }}</span>
                    <span style="font-size:11px;color:var(--text-dim);">sent</span>
                </td>
                <td data-label="Status">
                    @if($c->status === 'sent')
                        <span class="fp-badge fp-badge-active"><i class="bi bi-check-circle-fill"></i> Sent</span>
                    @elseif($c->status === 'draft')
                        <span class="fp-badge fp-badge-pending"><i class="bi bi-pencil-fill"></i> Draft</span>
                    @elseif($c->status === 'scheduled')
                        <span class="fp-badge fp-badge-pending"><i class="bi bi-clock-fill"></i> Scheduled</span>
                    @else
                        <span class="fp-badge fp-badge-inactive">{{ ucfirst($c->status) }}</span>
                    @endif
                </td>
                <td data-label="Date" style="font-size:12px;color:var(--text-dim);">
                    @if($c->sent_at)
                        {{ $c->sent_at->format('M d, Y') }}
                    @else
                        Created {{ $c->created_at->format('M d, Y') }}
                    @endif
                </td>
                <td data-label="Actions">
                    <div style="display:flex;gap:6px;">
                        @if($c->status === 'draft' || $c->status === 'scheduled')
                        <form action="{{ route('admin.campaigns.send', $c) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="fp-btn fp-btn-gold" style="padding:4px 12px;font-size:11px;" onclick="return confirm('Send this campaign now?')">
                                <i class="bi bi-send-fill"></i> Send
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('admin.campaigns.delete', $c) }}" class="fp-btn fp-btn-ghost" style="padding:4px 10px;color:#ef4444;font-size:11px;" onclick="return confirm('Delete this campaign? This cannot be undone.')">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="7" class="text-center py-5" style="color:var(--text-dim);">
                    <i class="bi bi-megaphone" style="font-size:36px;display:block;margin-bottom:12px;color:rgba(255,255,255,0.06);"></i>
                    No campaigns yet. <a href="{{ route('admin.campaigns.create') }}" style="color:var(--gold-400);">Create your first campaign</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($campaigns->hasPages())
<div style="margin-top:20px;">
    {{ $campaigns->links() }}
</div>
@endif
@endsection
