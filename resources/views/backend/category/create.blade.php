@extends('backend.app')
@section('title', 'Add Category — OwnPace Admin')
@section('page_title', 'Add Category')

@section('content')
<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>Category Details</h5></div>
    <div style="padding:24px;">
        <form action="{{ url('admin/category/store') }}" method="post">
            @csrf
            <div class="row g-3">
                <div class="col-sm-6">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                        <i class="bi bi-tag" style="color:var(--gold-500);"></i> Category Name <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" name="name" class="fp-form-control" placeholder="Enter category name" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="fp-btn fp-btn-gold"><i class="bi bi-check-lg"></i> Save Category</button>
                    <a href="{{ url('admin/category') }}" class="fp-btn fp-btn-ghost ms-2"><i class="bi bi-arrow-left"></i> Back</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection