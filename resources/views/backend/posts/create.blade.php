@extends('backend.app')
@section('title', 'Add Post — OwnPace Admin')
@section('page_title', 'Add Post')

@section('content')
<div class="fp-table-wrap">
    <div class="fp-table-header"><h5><i class="bi bi-plus-circle"></i> New Post</h5></div>
    <div style="padding:24px;">
        <form action="{{ url('/admin/workers/store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <div class="col-sm-6">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                        <i class="bi bi-person" style="color:var(--gold-500);"></i> Name <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" class="fp-form-control" name="title" placeholder="Enter name" required>
                </div>
                <div class="col-sm-6">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                        <i class="bi bi-tags" style="color:var(--gold-500);"></i> Category <span style="color:#ef4444;">*</span>
                    </label>
                    <select name="category_id" class="fp-form-control" required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                        <i class="bi bi-telephone" style="color:var(--gold-500);"></i> Contact Number <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="number" class="fp-form-control" name="contact_number" placeholder="Enter contact number" required>
                </div>
                <div class="col-sm-6">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                        <i class="bi bi-geo-alt" style="color:var(--gold-500);"></i> Division <span style="color:#ef4444;">*</span>
                    </label>
                    <select name="division" class="fp-form-control" required>
                        <option value="">Select Division</option>
                        <option value="Khulna">Khulna</option>
                        <option value="Dhaka">Dhaka</option>
                        <option value="Chittagong">Chittagong</option>
                        <option value="Rajshahi">Rajshahi</option>
                        <option value="Sylhet">Sylhet</option>
                        <option value="Barishal">Barishal</option>
                        <option value="Mymensingh">Mymensingh</option>
                        <option value="Rangpur">Rangpur</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                        <i class="bi bi-list-check" style="color:var(--gold-500);"></i> Status <span style="color:#ef4444;">*</span>
                    </label>
                    <select name="status" class="fp-form-control">
                        <option value="">Select Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                        <i class="bi bi-image" style="color:var(--gold-500);"></i> Profile Image <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="file" class="fp-form-control" name="file" id="file" accept="image/*,video/mp4" required>
                    <div class="mt-3">
                        <img id="preview" src="" class="d-none rounded-4" style="max-height:150px;border:1px solid var(--card-border);">
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="fp-btn fp-btn-gold"><i class="bi bi-check-lg"></i> Save</button>
                    <a href="{{ url('admin/workers') }}" class="fp-btn fp-btn-ghost ms-2"><i class="bi bi-arrow-left"></i> Back</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('file')?.addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    const file = e.target.files[0];
    if (!file) return;
    const allowedImages = ['image/jpeg','image/png','image/gif','image/webp'];
    if (allowedImages.includes(file.type)) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    } else if (file.type === 'video/mp4') {
        preview.classList.add('d-none');
        Swal.fire({icon:'success', title:'MP4 Video Selected', text:file.name, background:'#1A1A1E', color:'#F4F4F5'});
    } else {
        Swal.fire({icon:'error', title:'Invalid File', text:'Only Image and MP4 video allowed!', background:'#1A1A1E', color:'#F4F4F5'});
        e.target.value = '';
        preview.classList.add('d-none');
    }
});
@if(session('success'))
Swal.fire({icon:'success', title:'Success!', text:"{{ session('success') }}", background:'#1A1A1E', color:'#F4F4F5'});
@endif
@if(session('error'))
Swal.fire({icon:'error', title:'Error!', text:"{{ session('error') }}", background:'#1A1A1E', color:'#F4F4F5'});
@endif
@if ($errors->any())
let errorMessages = @json($errors->all());
Swal.fire({icon:'error', title:'Validation Error', html:errorMessages.join('<br>'), background:'#1A1A1E', color:'#F4F4F5'});
@endif
</script>
@endsection