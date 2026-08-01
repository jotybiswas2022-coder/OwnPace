@extends('backend.app')
@section('title', 'Product Fees — OwnPace Admin')
@section('page_title', 'Product Fees')

@push('styles')
<style>
@media (max-width: 768px) { .fp-table input[style*="width"] { width: 100% !important; } }
</style>
@endpush

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
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="fp-stat-card">
            <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-num">{{ $fees->count() }}</div>
            <div class="stat-label">Total Fees Configured</div>
        </div>
    </div>
</div>

<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>Manage Fees</h5></div>
    <table class="fp-table">
        <thead>
            <tr>
                <th>Fee Type</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fees ?? [] as $fee)
            <tr>
                <td data-label="Fee Type">
                    <strong style="color:var(--text-primary);">{{ $fee->name }}</strong>
                    @if($fee->description)
                    <br><small style="color:var(--text-dim);">{{ $fee->description }}</small>
                    @endif
                </td>
                <td data-label="Type">
                    <span class="fp-badge {{ $fee->type === 'percentage' ? 'fp-badge-active' : 'fp-badge-pending' }}">
                        {{ $fee->type === 'percentage' ? 'Percentage' : 'Fixed' }}
                    </span>
                </td>
                <td data-label="Amount">
                    @if($fee->type === 'percentage')
                    {{ $fee->amount }}%
                    @else
                    ₦{{ number_format($fee->amount, 0) }}
                    @endif
                </td>
                <td data-label="Status">
                    <span class="fp-badge {{ $fee->is_active ? 'fp-badge-active' : 'fp-badge-inactive' }}">
                        {{ $fee->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td data-label="Actions">
                    <form action="{{ route('admin.orders.fees.update', $fee) }}" method="POST" class="d-flex align-items-center gap-2 flex-wrap">
                        @csrf
                        <div class="d-flex align-items-center gap-1">
                            <label class="form-check-label" style="font-size:12px;color:var(--text-dim);white-space:nowrap;">Amount:</label>
                            <input type="number" name="amount" class="fp-form-control" value="{{ $fee->amount }}" style="width:100px;padding:6px 10px;" step="0.01" min="0">
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" class="form-check-input" id="active_{{ $fee->id }}" value="1" {{ $fee->is_active ? 'checked' : '' }} style="accent-color:var(--gold-500);">
                            <label for="active_{{ $fee->id }}" style="font-size:12px;color:var(--text-dim);cursor:pointer;">Active</label>
                        </div>
                        <button type="submit" class="fp-btn fp-btn-gold" style="padding:6px 14px;">Update</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="5" class="text-center py-4" style="color:var(--text-dim);">No fees configured</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection