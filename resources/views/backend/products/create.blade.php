@extends('backend.layouts.console')
@section('title', 'Add Product — '.storeName().' Admin')
@section('page_title', 'Add Product')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Shop', 'route' => 'admin.products.index'], ['label' => 'Add Product']]])
@endsection

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="os-card overflow-hidden">
        <div class="border-b border-ink/10 px-6 py-4">
            <h3 class="font-display text-sm font-bold text-ink">Product Details</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="sm:col-span-2">
                        <label for="name" class="os-label">Product Name <span class="text-ember">*</span></label>
                        <input type="text" id="name" name="name" class="os-input w-full" required>
                    </div>
                    <div>
                        <label for="category_id" class="os-label">Category <span class="text-ember">*</span></label>
                        <select id="category_id" name="category_id" class="os-input w-full" required>
                            @foreach($categories ?? [] as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="brand_id" class="os-label">Brand</label>
                        <select id="brand_id" name="brand_id" class="os-input w-full">
                            @foreach($brands ?? [] as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="price" class="os-label">Price <span class="text-ember">*</span></label>
                        <input type="number" id="price" name="price" class="os-input w-full" step="0.01" required>
                    </div>
                    <div>
                        <label for="base_price" class="os-label">Base Price <span class="text-ember">*</span></label>
                        <input type="number" id="base_price" name="base_price" class="os-input w-full" step="0.01" required>
                    </div>
                    <div>
                        <label for="shipping_fee" class="os-label">Shipping Fee</label>
                        <input type="number" id="shipping_fee" name="shipping_fee" class="os-input w-full" step="0.01" value="0">
                    </div>
                    <div>
                        <label for="supplier_id" class="os-label">Supplier</label>
                        <select id="supplier_id" name="supplier_id" class="os-input w-full">
                            @foreach($suppliers ?? [] as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label for="description" class="os-label">Description</label>
                    <textarea id="description" name="description" class="os-input w-full" rows="4"></textarea>
                </div>
                <div class="grid gap-5 sm:grid-cols-3">
                    <div>
                        <label for="stock_quantity" class="os-label">Stock Quantity</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" class="os-input w-full" value="0">
                    </div>
                    <div>
                        <label for="status" class="os-label">Status</label>
                        <select id="status" name="status" class="os-input w-full">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="featured" class="os-label">Featured</label>
                        <select id="featured" name="featured" class="os-input w-full">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="images" class="os-label">Images</label>
                    <input type="file" id="images" name="images[]" class="os-input w-full" multiple accept="image/*">
                    <p class="os-help-text">You can select multiple images. The first becomes the primary image.</p>
                </div>
                <div class="flex items-center gap-3 border-t border-ink/10 pt-5">
                    <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Save Product</button>
                    <a href="{{ route('admin.products.index') }}" class="os-btn os-btn-ghost"><i class="bi bi-arrow-left"></i> Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
