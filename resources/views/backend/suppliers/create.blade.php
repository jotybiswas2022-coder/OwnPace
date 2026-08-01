@extends('backend.app')
@section('title', 'Add Supplier — OwnPace Admin')
@section('page_title', 'Add Supplier')

@section('content')
<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>Supplier Details</h5></div>
    <div style="padding:24px;">
        <form action="{{ route('admin.suppliers.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-sm-6"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Company Name</label><input type="text" name="name" class="fp-form-control" required></div>
                <div class="col-sm-6"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Contact Person</label><input type="text" name="contact_person" class="fp-form-control"></div>
                <div class="col-sm-6"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Email</label><input type="email" name="email" class="fp-form-control"></div>
                <div class="col-sm-6"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Phone</label><input type="text" name="phone" class="fp-form-control"></div>
                <div class="col-12"><label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Address</label><textarea name="address" class="fp-form-control" rows="2"></textarea></div>
                <div class="col-12"><button type="submit" class="fp-btn fp-btn-gold"><i class="bi bi-check-lg"></i> Save Supplier</button></div>
            </div>
        </form>
    </div>
</div>
@endsection