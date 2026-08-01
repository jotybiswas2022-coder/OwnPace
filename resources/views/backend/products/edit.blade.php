@extends('backend.app')
@section('title', 'Edit Product — OwnPace Admin')
@section('page_title', 'Edit Product')

@push('styles')
<style>
.fp-img-grid{display:flex;flex-wrap:wrap;gap:12px;margin-top:8px}
.fp-img-item{position:relative;width:120px;height:120px;border-radius:10px;overflow:hidden;border:1px solid var(--card-border);background:var(--dark-800)}
.fp-img-item img{width:100%;height:100%;object-fit:cover}
.fp-img-del{position:absolute;top:4px;right:4px;width:28px;height:28px;border-radius:50%;border:none;background:rgba(239,68,68,.9);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:14px;transition:.2s;z-index:2}
.fp-img-del:hover{background:#ef4444;transform:scale(1.1)}
.fp-img-label{font-size:11px;color:var(--text-dim);margin-top:4px;text-align:center}
.fp-thumb-preview{width:100px;height:100px;border-radius:10px;overflow:hidden;border:1px solid var(--card-border);background:var(--dark-800);margin-top:6px}
.fp-thumb-preview img{width:100%;height:100%;object-fit:cover}
@media (max-width: 576px) {
    .fp-img-item{width:calc(50% - 6px);height:auto;aspect-ratio:1}
    .fp-thumb-preview{width:80px;height:80px}
}
</style>
@endpush

@section('content')
<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>Edit: {{ $product->name }}</h5></div>
    <div style="padding:24px;">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Product Name</label><input type="text" name="name" class="fp-form-control" value="{{ $product->name }}" required></div>
                <div class="col-sm-6 col-md-3"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Category</label><select name="category_id" class="fp-form-control" required>@foreach($categories ?? [] as $c)<option value="{{ $c->id }}" {{ $product->category_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach</select></div>
                <div class="col-sm-6 col-md-3"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Brand</label><select name="brand_id" class="fp-form-control">@foreach($brands ?? [] as $b)<option value="{{ $b->id }}" {{ $product->brand_id == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>@endforeach</select></div>
                <div class="col-sm-6 col-md-3"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Price (₦)</label><input type="number" name="price" class="fp-form-control" step="0.01" value="{{ $product->price }}" required></div>
                <div class="col-sm-6 col-md-3"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Base Price (₦)</label><input type="number" name="base_price" class="fp-form-control" step="0.01" value="{{ $product->base_price }}" required></div>
                <div class="col-sm-6 col-md-3"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Shipping Fee (₦)</label><input type="number" name="shipping_fee" class="fp-form-control" step="0.01" value="{{ $product->shipping_fee }}"></div>
                <div class="col-sm-6 col-md-3"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Supplier</label><select name="supplier_id" class="fp-form-control">@foreach($suppliers ?? [] as $s)<option value="{{ $s->id }}" {{ $product->supplier_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>@endforeach</select></div>
                <div class="col-12"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Description</label><textarea name="description" class="fp-form-control" rows="4">{{ $product->description }}</textarea></div>
                <div class="col-sm-6 col-md-4"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Stock Quantity</label><input type="number" name="stock_quantity" class="fp-form-control" value="{{ $product->stock_quantity ?? 0 }}"></div>
                <div class="col-sm-6 col-md-4"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Insurance Fee (₦)</label><input type="number" name="insurance_fee" class="fp-form-control" step="0.01" value="{{ $product->insurance_fee }}"></div>
                <div class="col-sm-6 col-md-4"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Interest Rate (%)</label><input type="number" name="interest_rate" class="fp-form-control" step="0.01" value="{{ $product->interest_rate }}"></div>
                <div class="col-sm-6 col-md-4"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Status</label><select name="status" class="fp-form-control"><option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option><option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option></select></div>
                <div class="col-sm-6 col-md-4"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Featured</label><select name="featured" class="fp-form-control"><option value="1" {{ $product->featured ? 'selected' : '' }}>Yes</option><option value="0" {{ !$product->featured ? 'selected' : '' }}>No</option></select></div>

                {{-- Thumbnail --}}
                <div class="col-sm-6">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Thumbnail</label>
                    <input type="file" name="thumbnail" class="fp-form-control" accept="image/*">
                    @if($product->thumbnail)
                        <div class="fp-thumb-preview">
                            <img src="{{ asset('storage/'.$product->thumbnail) }}" alt="Thumbnail">
                        </div>
                    @endif
                </div>

                {{-- Existing Images --}}
                <div class="col-12">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Existing Images</label>
                    @if($product->images && $product->images->count() > 0)
                        <div class="fp-img-grid">
                            @foreach($product->images as $img)
                                <div class="fp-img-item" id="img-wrap-{{ $img->id }}">
                                    <img src="{{ asset('storage/'.$img->image_path) }}" alt="">
                                    <button type="button" class="fp-img-del" onclick="deleteImage({{ $img->id }})"><i class="bi bi-x"></i></button>
                                    @if($img->is_primary)
                                        <div class="fp-img-label" style="color:var(--gold-500)">Primary</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="color:var(--text-dim);font-size:13px;margin:8px 0 0;">No images uploaded yet.</p>
                    @endif
                </div>

                {{-- New Images --}}
                <div class="col-12"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Add New Images</label><input type="file" name="images[]" class="fp-form-control" multiple accept="image/*"></div>

                <div class="col-12"><button type="submit" class="fp-btn fp-btn-gold"><i class="bi bi-check-lg"></i> Update Product</button></div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteImage(id) {
    Swal.fire({
        title: 'Delete Image?',
        text: 'This will permanently remove this image.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#71717A',
        confirmButtonText: 'Yes, delete it',
        background: '#1A1A1E',
        color: '#F4F4F5'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/admin/products/delete-image/' + id)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('img-wrap-' + id).remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Image has been removed.',
                            background: '#1A1A1E',
                            color: '#F4F4F5',
                            confirmButtonColor: '#71717A',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
        }
    });
}
</script>
@endsection