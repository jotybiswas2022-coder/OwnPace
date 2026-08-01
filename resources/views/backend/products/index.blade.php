@extends('backend.app')
@section('title', 'Products — OwnPace Admin')
@section('page_title', 'Products')

@push('styles')
<style>
@media (max-width: 768px) {
    .fp-products-header { flex-direction: column; gap: 10px; align-items: stretch !important; }
    .fp-products-header a { width: 100%; justify-content: center; }
    .fp-table-resp { overflow-x: auto; -webkit-overflow-scrolling: touch; }
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
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 fp-products-header">
    <p class="mb-0" style="color:var(--text-muted);">{{ $products->count() ?? 0 }} products total</p>
    <a href="{{ route('admin.products.create') }}" class="fp-btn fp-btn-gold"><i class="bi bi-plus-lg"></i> Add Product</a>
</div>

<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>All Products</h5></div>
    <div class="fp-table-resp">
    <table class="fp-table">
        <thead><tr><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($products ?? [] as $p)
            <tr>
                <td data-label="Name"><strong style="color:var(--text-primary);">{{ Str::limit($p->name, 40) }}</strong></td>
                <td data-label="Category">{{ $p->category?->name ?? '—' }}</td>
                <td data-label="Price" style="color:var(--gold-400);font-weight:600;">₦{{ number_format($p->price, 0) }}</td>
                <td data-label="Stock">{{ $p->stock ?? 'N/A' }}</td>
                <td data-label="Status"><span class="fp-badge {{ $p->status == 'active' ? 'fp-badge-active' : 'fp-badge-inactive' }}">{{ ucfirst($p->status) }}</span></td>
                <td data-label="Actions">
                    <a href="{{ route('admin.products.edit', $p) }}" class="fp-btn fp-btn-ghost" style="padding:6px 12px;"><i class="bi bi-pencil-fill"></i></a>
                    <a href="{{ route('admin.products.delete', $p) }}" class="fp-btn fp-btn-ghost" style="padding:6px 12px;color:#ef4444;" onclick="return confirm('Delete this product?')"><i class="bi bi-trash-fill"></i></a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-4" style="color:var(--text-dim);">No products yet</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection