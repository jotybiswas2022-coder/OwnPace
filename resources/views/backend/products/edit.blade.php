@extends('backend.layouts.console')
@section('title', 'Edit Product — '.storeName().' Admin')
@section('page_title', 'Edit Product')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Shop', 'route' => 'admin.products.index'], ['label' => $product->name]]])
@endsection

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="os-card overflow-hidden">
        <div class="border-b border-ink/10 px-6 py-4">
            <h3 class="font-display text-sm font-bold text-ink">Edit: {{ $product->name }}</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="sm:col-span-2">
                        <label for="name" class="os-label">Product Name <span class="text-ember">*</span></label>
                        <input type="text" id="name" name="name" class="os-input w-full" value="{{ $product->name }}" required>
                    </div>
                    <div>
                        <label for="category_id" class="os-label">Category <span class="text-ember">*</span></label>
                        <select id="category_id" name="category_id" class="os-input w-full" required>
                            @foreach($categories ?? [] as $c)
                            <option value="{{ $c->id }}" {{ $product->category_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="brand_id" class="os-label">Brand</label>
                        <select id="brand_id" name="brand_id" class="os-input w-full">
                            @foreach($brands ?? [] as $b)
                            <option value="{{ $b->id }}" {{ $product->brand_id == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="price" class="os-label">Price <span class="text-ember">*</span></label>
                        <input type="number" id="price" name="price" class="os-input w-full" step="0.01" value="{{ $product->price }}" required>
                    </div>
                    <div>
                        <label for="base_price" class="os-label">Base Price <span class="text-ember">*</span></label>
                        <input type="number" id="base_price" name="base_price" class="os-input w-full" step="0.01" value="{{ $product->base_price }}" required>
                    </div>
                    <div>
                        <label for="shipping_fee" class="os-label">Shipping Fee</label>
                        <input type="number" id="shipping_fee" name="shipping_fee" class="os-input w-full" step="0.01" value="{{ $product->shipping_fee }}">
                    </div>
                    <div>
                        <label for="supplier_id" class="os-label">Supplier</label>
                        <select id="supplier_id" name="supplier_id" class="os-input w-full">
                            @foreach($suppliers ?? [] as $s)
                            <option value="{{ $s->id }}" {{ $product->supplier_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label for="description" class="os-label">Description</label>
                    <textarea id="description" name="description" class="os-input w-full" rows="4">{{ $product->description }}</textarea>
                </div>
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label for="stock_quantity" class="os-label">Stock Quantity</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" class="os-input w-full" value="{{ $product->stock_quantity ?? 0 }}">
                    </div>
                    <div>
                        <label for="insurance_fee" class="os-label">Insurance Fee</label>
                        <input type="number" id="insurance_fee" name="insurance_fee" class="os-input w-full" step="0.01" value="{{ $product->insurance_fee }}">
                    </div>
                    <div>
                        <label for="interest_rate" class="os-label">Interest Rate (%)</label>
                        <input type="number" id="interest_rate" name="interest_rate" class="os-input w-full" step="0.01" value="{{ $product->interest_rate }}">
                    </div>
                    <div>
                        <label for="status" class="os-label">Status</label>
                        <select id="status" name="status" class="os-input w-full">
                            <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="featured" class="os-label">Featured</label>
                        <select id="featured" name="featured" class="os-input w-full">
                            <option value="1" {{ $product->featured ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$product->featured ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>

                {{-- Thumbnail --}}
                <div>
                    <label for="thumbnail" class="os-label">Thumbnail</label>
                    <input type="file" id="thumbnail" name="thumbnail" class="os-input w-full" accept="image/*">
                    @if($product->thumbnail)
                        <img src="{{ asset('storage/'.$product->thumbnail) }}" alt="{{ $product->name }} thumbnail" class="mt-3 h-24 w-24 rounded-xl border border-ink/10 object-cover">
                    @endif
                </div>

                {{-- Existing Images --}}
                <div>
                    <p class="os-label">Existing Images</p>
                    @if($product->images && $product->images->count() > 0)
                        <div class="flex flex-wrap gap-3">
                            @foreach($product->images as $img)
                                <div id="img-wrap-{{ $img->id }}" class="relative h-28 w-28 overflow-hidden rounded-xl border border-ink/10 bg-ink/5">
                                    <img src="{{ asset('storage/'.$img->image_path) }}" alt="{{ $product->name }} image" class="h-full w-full object-cover">
                                    <button type="button" class="absolute right-1.5 top-1.5 flex h-7 w-7 items-center justify-center rounded-full bg-ember text-white shadow transition-transform hover:scale-110" onclick="deleteImage({{ $img->id }})" aria-label="Delete image">
                                        <i class="bi bi-x"></i>
                                    </button>
                                    @if($img->is_primary)
                                        <span class="absolute bottom-1.5 left-1.5 rounded-full bg-mango px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-ink">Primary</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate">No images uploaded yet.</p>
                    @endif
                </div>

                {{-- New Images --}}
                <div>
                    <label for="images" class="os-label">Add New Images</label>
                    <input type="file" id="images" name="images[]" class="os-input w-full" multiple accept="image/*">
                </div>

                <div class="flex items-center gap-3 border-t border-ink/10 pt-5">
                    <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Update Product</button>
                    <a href="{{ route('admin.products.index') }}" class="os-btn os-btn-ghost"><i class="bi bi-arrow-left"></i> Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function deleteImage(id) {
    if (!confirm('Delete this image? This cannot be undone.')) return;
    fetch('/admin/products/delete-image/' + id)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const wrap = document.getElementById('img-wrap-' + id);
                wrap?.remove();
                window.flash?.('Image removed.', 'success');
            } else {
                window.flash?.(data.message || 'Could not delete image.', 'error');
            }
        })
        .catch(() => window.flash?.('Something went wrong.', 'error'));
}
</script>
@endsection
