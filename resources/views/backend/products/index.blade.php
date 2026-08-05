@extends('backend.layouts.console')
@section('title', 'Products — '.storeName().' Admin')
@section('page_title', 'Products')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Shop'], ['label' => 'Products']]])
@endsection

@section('content')
<div class="os-card overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/10 px-5 py-4">
        <div>
            <h3 class="font-display text-sm font-bold text-ink">All Products</h3>
            <p class="mt-0.5 text-xs text-slate">{{ $products->count() ?? 0 }} products total</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.products.import') }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-upload"></i> Import CSV</a>
            <a href="{{ route('admin.products.create') }}" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-plus-lg"></i> Add Product</a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th class="w-28">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($products ?? [] as $p)
                <tr>
                    <td data-label="Product">
                        <div class="flex items-center gap-3">
                            @if($p->primaryImage)
                                <img src="{{ imageUrl($p->primaryImage->image_path) }}" alt="{{ $p->name }}" class="h-10 w-10 flex-shrink-0 rounded-lg border border-ink/10 object-cover">
                            @else
                                <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-ink/5 text-slate"><i class="bi bi-image"></i></span>
                            @endif
                            <span class="font-semibold text-ink">{{ Str::limit($p->name, 40) }}</span>
                        </div>
                    </td>
                    <td data-label="Category" class="text-slate">{{ $p->category?->name ?: '—' }}</td>
                    <td data-label="Price" class="font-mono font-semibold text-mango-ink">{{ formatPrice($p->price, 0) }}</td>
                    <td data-label="Stock" class="text-slate">{{ $p->stock_quantity ?? 'N/A' }}</td>
                    <td data-label="Status">
                        <span class="os-chip {{ $p->status == 'active' ? 'os-chip-grass' : 'os-chip-ember' }}">{{ ucfirst($p->status) }}</span>
                    </td>
                    <td data-label="Actions" class="whitespace-nowrap">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.products.edit', $p) }}" class="os-btn os-btn-ghost os-btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="{{ route('admin.products.delete', $p) }}" class="os-btn os-btn-danger os-btn-sm" title="Delete" onclick="return confirm('Delete this product?')"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-14 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-box-seam"></i></div>
                        <p class="mt-4 font-semibold text-ink">No products yet</p>
                        <p class="mt-1 text-sm text-slate">Add your first product, or import a batch from a CSV file.</p>
                        <div class="mt-4 flex items-center justify-center gap-2">
                            <a href="{{ route('admin.products.create') }}" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-plus-lg"></i> Add Product</a>
                            <a href="{{ route('admin.products.import') }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-upload"></i> Import CSV</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
