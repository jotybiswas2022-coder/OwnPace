@extends('backend.app')
@section('title', 'Suppliers — OwnPace Admin')
@section('page_title', 'Suppliers')

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
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <p class="mb-0" style="color:var(--text-muted);">{{ $suppliers->count() ?? 0 }} suppliers</p>
    <a href="{{ route('admin.suppliers.create') }}" class="fp-btn fp-btn-gold"><i class="bi bi-plus-lg"></i> Add Supplier</a>
</div>
<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>All Suppliers</h5></div>
    <table class="fp-table">
        <thead><tr><th>Name</th><th>Contact</th><th>Email</th><th>Products</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($suppliers ?? [] as $s)
            <tr>
                <td data-label="Name"><strong style="color:var(--text-primary);">{{ $s->name }}</strong></td>
                <td data-label="Contact">{{ $s->contact_person ?? '—' }}</td>
                <td data-label="Email">{{ $s->email ?? '—' }}</td>
                <td data-label="Products">{{ $s->products->count() }}</td>
                <td data-label="Actions">
                    <a href="{{ route('admin.suppliers.edit', $s) }}" class="fp-btn fp-btn-ghost" style="padding:4px 10px;"><i class="bi bi-pencil-fill"></i></a>
                    <a href="{{ route('admin.suppliers.delete', $s) }}" class="fp-btn fp-btn-ghost" style="padding:4px 10px;color:#ef4444;" onclick="return confirm('Delete this supplier?')"><i class="bi bi-trash-fill"></i></a>
                </td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="5" class="text-center py-4" style="color:var(--text-dim);">No suppliers</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection