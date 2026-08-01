@extends('backend.app')
@section('title', 'Contacts — OwnPace Admin')
@section('page_title', 'Contact Messages')

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

@if (session('success'))
<div class="fp-table-wrap mb-4" style="border-left:3px solid #4ade80;">
    <div class="p-3" style="color:#4ade80;font-size:14px;">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
    </div>
</div>
@endif

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <p class="mb-0" style="color:var(--text-muted);">{{ $contacts->count() ?? 0 }} total messages</p>
</div>

<div class="fp-table-wrap">
    <div class="fp-table-header"><h5><i class="bi bi-envelope"></i> Customer Messages</h5></div>
    <div class="table-responsive">
        <table class="fp-table">
            <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Message</th><th>Date</th><th>Time</th></tr></thead>
            <tbody>
                @forelse ($contacts as $contact)
                    <tr>
                        <td data-label="#" style="color:var(--text-dim);">{{ $loop->iteration }}</td>
                        <td data-label="Name"><strong style="color:var(--text-primary);">{{ $contact->name }}</strong></td>
                        <td data-label="Email" style="color:var(--text-muted);font-size:13px;">{{ $contact->email }}</td>
                        <td data-label="Message">
                            <button class="fp-btn fp-btn-ghost" style="padding:4px 10px;font-size:12px;" data-bs-toggle="modal" data-bs-target="#msgModal{{ $contact->id }}">
                                <i class="bi bi-chat-dots" style="color:var(--gold-500);"></i> View
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="msgModal{{ $contact->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content" style="background:var(--card-dark);border:1px solid var(--card-border);border-radius:16px;">
                                        <div class="modal-header" style="border-bottom:1px solid var(--card-border);padding:18px 24px;">
                                            <h5 class="modal-title" style="color:var(--text-primary);font-family:'Syne',sans-serif;font-size:15px;">
                                                <i class="bi bi-chat-dots me-2" style="color:var(--gold-500);"></i> Message from {{ $contact->name }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter:invert(0.6);"></button>
                                        </div>
                                        <div class="modal-body p-4" style="color:var(--text-muted);font-size:14px;line-height:1.7;">
                                            {{ $contact->message }}
                                        </div>
                                        <div class="modal-footer" style="border-top:1px solid var(--card-border);padding:16px 24px;">
                                            <button type="button" class="fp-btn fp-btn-ghost" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Date" style="font-size:12px;color:var(--text-dim);">
                            {{ \Carbon\Carbon::parse($contact->created_at)->timezone('Asia/Dhaka')->format('d M Y') }}
                        </td>
                        <td data-label="Time" style="font-size:12px;color:var(--text-dim);">
                            {{ \Carbon\Carbon::parse($contact->created_at)->timezone('Asia/Dhaka')->format('h:i A') }}
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row"><td colspan="6" class="text-center py-5" style="color:var(--text-dim);">
                        <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:8px;color:var(--card-border);"></i>
                        No messages yet
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection