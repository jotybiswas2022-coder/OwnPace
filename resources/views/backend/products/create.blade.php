@extends('backend.app')
@section('title', 'Add Product — OwnPace Admin')
@section('page_title', 'Add Product')

@section('content')
<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>Product Details</h5></div>
    <div style="padding:24px;">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Product Name</label><input type="text" name="name" class="fp-form-control" required></div>
                <div class="col-sm-6 col-md-3"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Category</label><select name="category_id" class="fp-form-control" required>@foreach($categories ?? [] as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
                <div class="col-sm-6 col-md-3"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Brand</label><select name="brand_id" class="fp-form-control">@foreach($brands ?? [] as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach</select></div>
                <div class="col-sm-6 col-md-3"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Price (₦)</label><input type="number" name="price" class="fp-form-control" step="0.01" required></div>
                <div class="col-sm-6 col-md-3"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Base Price (₦)</label><input type="number" name="base_price" class="fp-form-control" step="0.01" required></div>
                <div class="col-sm-6 col-md-3"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Shipping Fee (₦)</label><input type="number" name="shipping_fee" class="fp-form-control" step="0.01" value="0"></div>
                <div class="col-sm-6 col-md-3"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Supplier</label><select name="supplier_id" class="fp-form-control">@foreach($suppliers ?? [] as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select></div>
                <div class="col-12"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Description</label><textarea name="description" class="fp-form-control" rows="4"></textarea></div>
                <div class="col-sm-6 col-md-4"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Stock Quantity</label><input type="number" name="stock_quantity" class="fp-form-control" value="0"></div>
                <div class="col-sm-6 col-md-4"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Status</label><select name="status" class="fp-form-control"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                <div class="col-sm-6 col-md-4"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Featured</label><select name="featured" class="fp-form-control"><option value="0">No</option><option value="1">Yes</option></select></div>
                <div class="col-12"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Images</label><input type="file" name="images[]" class="fp-form-control" multiple accept="image/*"></div>
                <div class="col-12"><button type="submit" class="fp-btn fp-btn-gold"><i class="bi bi-check-lg"></i> Save Product</button></div>
            </div>
        </form>
    </div>
</div>
@endsection