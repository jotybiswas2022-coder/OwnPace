@extends('backend.layouts.console')
@section('title', 'Edit Brand — '.storeName().' Admin')
@section('page_title', 'Edit Brand')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Shop', 'route' => 'admin.brands.index'], ['label' => $brand->name]]])
@endsection

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="os-card overflow-hidden">
        <div class="border-b border-ink/10 px-6 py-4">
            <h3 class="font-display text-sm font-bold text-ink">Edit: {{ $brand->name }}</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.brands.update', $brand) }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="brand_name" class="os-label">Brand Name <span class="text-ember">*</span></label>
                        <input type="text" id="brand_name" name="name" class="os-input w-full" value="{{ $brand->name }}" required>
                    </div>
                    <div>
                        <label for="brand_logo" class="os-label">Logo URL</label>
                        <input type="text" id="brand_logo" name="logo" class="os-input w-full" value="{{ $brand->logo }}" placeholder="https://…">
                    </div>
                    <div>
                        <label for="brand_website" class="os-label">Website</label>
                        <input type="url" id="brand_website" name="website" class="os-input w-full" value="{{ $brand->website }}" placeholder="https://…">
                    </div>
                </div>
                <div>
                    <label for="brand_desc" class="os-label">Description</label>
                    <textarea id="brand_desc" name="description" class="os-input w-full" rows="3">{{ $brand->description }}</textarea>
                </div>
                <div class="flex items-center gap-3 border-t border-ink/10 pt-5">
                    <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Update Brand</button>
                    <a href="{{ route('admin.brands.index') }}" class="os-btn os-btn-ghost"><i class="bi bi-arrow-left"></i> Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
