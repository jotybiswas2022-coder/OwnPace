@extends('backend.app')
@section('title', 'Brands — OwnPace Admin')
@section('page_title', 'Brands')

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
    <p class="mb-0" style="color:var(--text-muted);">{{ $brands->count() ?? 0 }} brands</p>
    <a href="{{ route('admin.brands.create') }}" class="fp-btn fp-btn-gold"><i class="bi bi-plus-lg"></i> Add Brand</a>
</div>
<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>All Brands</h5></div>
    <table class="fp-table">
        <thead><tr><th>Name</th><th>Description</th><th>Products</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($brands ?? [] as $b)
            <tr>
                <td data-label="Name"><strong style="color:var(--text-primary);">{{ $b->name }}</strong></td>
                <td data-label="Description">{{ Str::limit($b->description, 50) ?? '—' }}</td>
                <td data-label="Products">{{ $b->products->count() }}</td>
                <td data-label="Actions">
                    <a href="{{ route('admin.brands.edit', $b) }}" class="fp-btn fp-btn-ghost" style="padding:4px 10px;"><i class="bi bi-pencil-fill"></i></a>
                    <a href="{{ route('admin.brands.delete', $b) }}" class="fp-btn fp-btn-ghost" style="padding:4px 10px;color:#ef4444;" onclick="return confirm('Delete this brand?')"><i class="bi bi-trash-fill"></i></a>
                </td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="4" class="text-center py-4" style="color:var(--text-dim);">No brands</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
