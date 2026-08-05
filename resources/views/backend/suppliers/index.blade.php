@extends('backend.layouts.console')
@section('title', 'Suppliers — '.storeName().' Admin')
@section('page_title', 'Suppliers')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Shop'], ['label' => 'Suppliers']]])
@endsection

@section('content')
<div class="os-card overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/10 px-5 py-4">
        <div>
            <h3 class="font-display text-sm font-bold text-ink">All Suppliers</h3>
            <p class="mt-0.5 text-xs text-slate">{{ $suppliers->count() ?? 0 }} suppliers</p>
        </div>
        <a href="{{ route('admin.suppliers.create') }}" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-plus-lg"></i> Add Supplier</a>
    </div>
    <div class="overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr><th>Name</th><th>Contact</th><th>Email</th><th>Products</th><th class="w-28">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($suppliers ?? [] as $s)
                <tr>
                    <td data-label="Name" class="font-semibold text-ink">{{ $s->name }}</td>
                    <td data-label="Contact" class="text-slate">{{ $s->contact_person ?: '—' }}</td>
                    <td data-label="Email" class="text-slate">{{ $s->email ?: '—' }}</td>
                    <td data-label="Products" class="text-slate">{{ $s->products->count() }}</td>
                    <td data-label="Actions" class="whitespace-nowrap">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.suppliers.edit', $s) }}" class="os-btn os-btn-ghost os-btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="{{ route('admin.suppliers.delete', $s) }}" class="os-btn os-btn-danger os-btn-sm" title="Delete" onclick="return confirm('Delete this supplier?')"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-14 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-truck"></i></div>
                        <p class="mt-4 font-semibold text-ink">No suppliers yet</p>
                        <p class="mt-1 text-sm text-slate">Add your first supplier to track where products come from.</p>
                        <a href="{{ route('admin.suppliers.create') }}" class="os-btn os-btn-brand os-btn-sm mt-4"><i class="bi bi-plus-lg"></i> Add Supplier</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
