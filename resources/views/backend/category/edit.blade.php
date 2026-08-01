@extends('backend.layouts.console')
@section('title', 'Edit Category — '.storeName().' Admin')
@section('page_title', 'Edit Category')

@section('content')

<div class="mx-auto max-w-xl">
    <a href="{{ url('admin/category') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-semibold text-slate transition-colors hover:text-brand">
        <i class="bi bi-arrow-left"></i> Back to Categories
    </a>

    <div class="os-card p-6 sm:p-8">
        <div class="mb-6 flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-mango/15 text-xl text-mango-deep"><i class="bi bi-tag-fill"></i></span>
            <div>
                <h2 class="font-display text-lg font-bold text-ink">Edit Category</h2>
                <p class="text-sm text-slate">Update the category name below.</p>
            </div>
        </div>

        <form action="{{ url('admin/category/update/'.$category->id) }}" method="POST">
            @csrf
            <label for="name" class="mb-1.5 block text-xs font-semibold text-slate">Category Name <span class="text-ember">*</span></label>
            <input type="text" id="name" name="name" class="os-input" value="{{ old('name', $category->name) }}" required>
            @error('name')
            <p class="mt-1.5 text-xs text-ember">{{ $message }}</p>
            @enderror

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="os-btn os-btn-mango"><i class="bi bi-check-lg"></i> Save Changes</button>
                <a href="{{ url('admin/category') }}" class="os-btn os-btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
