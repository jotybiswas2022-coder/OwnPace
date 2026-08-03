@extends('backend.app')
@section('title', 'Product Requests — OwnPace Admin')
@section('page_title', 'Product Requests')

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
    <div class="fp-table-header"><h5>New Product Requests</h5></div>
    <table class="fp-table">
        <thead><tr><th>Customer</th><th>Product Name</th><th>Details</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($requests ?? [] as $r)
            <tr>
                <td data-label="Customer"><strong style="color:var(--text-primary);">{{ $r->user?->name ?? 'N/A' }}</strong></td>
                <td data-label="Product Name">{{ $r->product_name }}</td>
                <td data-label="Details" style="max-width:220px;">
                    @if($r->product_url)<a href="{{ $r->product_url }}" target="_blank" rel="noopener" style="color:var(--gold-400);font-size:12px;"><i class="bi bi-box-arrow-up-right"></i> Link</a>@endif
                    @if($r->reason)<small style="display:block;color:var(--text-dim);margin-top:2px;">Why: {{ Str::limit($r->reason, 60) }}</small>@endif
                </td>
                <td data-label="Date" style="color:var(--text-dim);font-size:12px;">{{ $r->created_at->format('M d, Y') }}</td>
                <td data-label="Status"><span class="fp-badge {{ $r->status == 'approved' ? 'fp-badge-active' : ($r->status == 'rejected' ? 'fp-badge-inactive' : 'fp-badge-pending') }}">{{ ucfirst(str_replace('_', ' ', $r->status)) }}</span></td>
                <td data-label="Actions">
                    <form action="{{ route('admin.requests.product-requests.update', $r) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <select name="status" class="fp-form-control" style="width:auto;">
                            <option value="under_review" {{ $r->status == 'under_review' ? 'selected' : '' }}>Under review</option>
                            <option value="approved" {{ $r->status == 'approved' ? 'selected' : '' }}>Approve</option>
                            <option value="rejected" {{ $r->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                            <option value="submitted" {{ $r->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        </select>
                        <input type="text" name="admin_notes" placeholder="Note" class="fp-form-control" style="width:130px;" value="{{ $r->admin_notes }}">
                        <button type="submit" class="fp-btn fp-btn-gold" style="padding:6px 12px;">Update</button>
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