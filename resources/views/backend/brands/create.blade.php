@extends('backend.app')
@section('title', 'Add Brand — OwnPace Admin')
@section('page_title', 'Add Brand')

@section('content')
<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>Brand Details</h5></div>
    <div style="padding:24px;">
        <form action="{{ route('admin.brands.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-sm-6"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Brand Name</label><input type="text" name="name" class="fp-form-control" required></div>
                <div class="col-sm-6"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Logo URL</label><input type="text" name="logo" class="fp-form-control"></div>
                <div class="col-sm-6"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Website</label><input type="url" name="website" class="fp-form-control"></div>
                <div class="col-12"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Description</label><textarea name="description" class="fp-form-control" rows="2"></textarea></div>
                <div class="col-12"><button type="submit" class="fp-btn fp-btn-gold"><i class="bi bi-check-lg"></i> Save Brand</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
