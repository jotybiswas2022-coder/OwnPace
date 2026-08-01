@extends('backend.layouts.console')
@section('title', 'Categories — '.storeName().' Admin')
@section('page_title', 'Categories')

@section('content')

@if(session('success'))
<div class="mb-4 flex items-center gap-2 rounded-lg border-l-4 border-grass bg-grass/10 px-4 py-3 text-sm text-grass" role="status">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 flex items-center gap-2 rounded-lg border-l-4 border-ember bg-ember/10 px-4 py-3 text-sm text-ember" role="status">
    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
</div>
@endif

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="font-display text-xl font-bold text-ink">All Categories</h2>
        <p class="text-sm text-slate">{{ $categories->count() ?? 0 }} categories</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <input type="text" id="categorySearch" class="os-input" placeholder="Search categories…" style="max-width: 220px;" aria-label="Search categories">
        <a href="{{ url('admin/category/create') }}" class="os-btn os-btn-mango os-btn-sm"><i class="bi bi-plus-lg"></i> Add Category</a>
    </div>
</div>

<!-- Card grid — no tables, no modals -->
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    @forelse($categories as $category)
    <div class="os-card os-card-hover flex flex-col p-5 category-row">
        <div class="flex items-start justify-between gap-3">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-mango/15 text-xl text-mango-deep">
                <i class="bi bi-tag-fill"></i>
            </span>
            <span class="os-chip os-chip-slate">{{ $loop->iteration }}</span>
        </div>
        <h3 class="mt-4 font-display text-base font-bold text-ink">{{ $category->name }}</h3>
        <p class="mt-1 text-xs text-slate">Created {{ $category->created_at->format('M d, Y H:i') }}</p>
        <div class="mt-4 flex items-center gap-2 border-t border-ink/5 pt-4">
            <a href="{{ url('admin/category/edit/'.$category->id) }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-pencil-fill"></i> Edit</a>
            <a href="{{ url('admin/category/delete/'.$category->id) }}" class="os-btn os-btn-danger os-btn-sm" onclick="return confirmDelete(event, 'Delete this category? This cannot be undone.')"><i class="bi bi-trash-fill"></i> Delete</a>
        </div>
    </div>
    @empty
    <div class="col-span-full rounded-2xl border border-dashed border-ink/15 bg-white p-14 text-center">
        <i class="bi bi-tags text-4xl text-ink/15"></i>
        <p class="mt-3 text-sm font-medium text-ink">No categories yet</p>
        <a href="{{ url('admin/category/create') }}" class="os-btn os-btn-mango os-btn-sm mt-5"><i class="bi bi-plus-lg"></i> Add your first category</a>
    </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script>
    // Lightweight search across category cards — no modals, no tables.
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('categorySearch');
        if (!searchInput) return;
        searchInput.addEventListener('keyup', function () {
            const filter = searchInput.value.toLowerCase();
            document.querySelectorAll('.category-row').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
    });

    // Confirm before delete (SweetAlert toast style, no Bootstrap modal).
    function confirmDelete(event, message) {
        event.preventDefault();
        const href = event.currentTarget.getAttribute('href');
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#E0483E',
                cancelButtonColor: '#6B7280',
                background: '#ffffff',
                color: '#1A1B23',
            }).then(result => {
                if (result.isConfirmed) window.location.href = href;
            });
        } else if (window.confirm(message)) {
            window.location.href = href;
        }
        return false;
    }
</script>
@endpush
