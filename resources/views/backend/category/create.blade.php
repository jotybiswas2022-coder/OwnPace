@extends('backend.layouts.console')
@section('title', 'Add Category — '.storeName().' Admin')
@section('page_title', 'Add Category')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Shop', 'route' => 'admin.category.index'], ['label' => 'Add Category']]])
@endsection

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="os-card overflow-hidden">
        <div class="border-b border-ink/10 px-6 py-4">
            <h3 class="font-display text-sm font-bold text-ink">Category Details</h3>
        </div>
        <div class="p-6">
            <form action="{{ url('admin/category/store') }}" method="post" class="space-y-5">
                @csrf
                <div>
                    <label for="name" class="os-label"><i class="bi bi-tag mr-1 text-mango-deep"></i> Category Name <span class="text-ember">*</span></label>
                    <input type="text" id="name" name="name" class="os-input w-full" placeholder="Enter category name" required>
                </div>
                <div class="flex items-center gap-3 border-t border-ink/10 pt-5">
                    <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Save Category</button>
                    <a href="{{ url('admin/category') }}" class="os-btn os-btn-ghost"><i class="bi bi-arrow-left"></i> Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
