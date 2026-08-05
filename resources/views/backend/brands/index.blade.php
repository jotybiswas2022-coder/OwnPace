@extends('backend.layouts.console')
@section('title', 'Brands — '.storeName().' Admin')
@section('page_title', 'Brands')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Shop'], ['label' => 'Brands']]])
@endsection

@section('content')
<div class="os-card overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/10 px-5 py-4">
        <div>
            <h3 class="font-display text-sm font-bold text-ink">All Brands</h3>
            <p class="mt-0.5 text-xs text-slate">{{ $brands->count() ?? 0 }} brands</p>
        </div>
        <a href="{{ route('admin.brands.create') }}" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-plus-lg"></i> Add Brand</a>
    </div>
    <div class="overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr><th>Name</th><th>Description</th><th>Products</th><th class="w-28">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($brands ?? [] as $b)
                <tr>
                    <td data-label="Name" class="font-semibold text-ink">{{ $b->name }}</td>
                    <td data-label="Description" class="text-slate">{{ Str::limit($b->description, 50) ?: '—' }}</td>
                    <td data-label="Products" class="text-slate">{{ $b->products->count() }}</td>
                    <td data-label="Actions" class="whitespace-nowrap">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.brands.edit', $b) }}" class="os-btn os-btn-ghost os-btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="{{ route('admin.brands.delete', $b) }}" class="os-btn os-btn-danger os-btn-sm" title="Delete" onclick="return confirm('Delete this brand?')"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-14 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-building"></i></div>
                        <p class="mt-4 font-semibold text-ink">No brands yet</p>
                        <p class="mt-1 text-sm text-slate">Add your first brand to organise products by maker.</p>
                        <a href="{{ route('admin.brands.create') }}" class="os-btn os-btn-brand os-btn-sm mt-4"><i class="bi bi-plus-lg"></i> Add Brand</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
