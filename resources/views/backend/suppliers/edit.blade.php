@extends('backend.app')
@section('title', 'Edit Supplier — OwnPace Admin')
@section('page_title', 'Edit Supplier')

@section('content')
<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>Edit Supplier</h5></div>
    <div style="padding:24px;">
        <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-sm-6"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Company Name</label><input type="text" name="name" class="fp-form-control" value="{{ $supplier->name }}" required></div>
                <div class="col-sm-6"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Contact Person</label><input type="text" name="contact_person" class="fp-form-control" value="{{ $supplier->contact_person }}"></div>
                <div class="col-sm-6"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Email</label><input type="email" name="email" class="fp-form-control" value="{{ $supplier->email }}"></div>
                <div class="col-sm-6"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Phone</label><input type="text" name="phone" class="fp-form-control" value="{{ $supplier->phone }}"></div>
                <div class="col-12"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Address</label><textarea name="address" class="fp-form-control" rows="2">{{ $supplier->address }}</textarea></div>
                <div class="col-12"><button type="submit" class="fp-btn fp-btn-gold"><i class="bi bi-check-lg"></i> Update Supplier</button></div>
            </div>
        </form>
    </div>
</div>
@endsection