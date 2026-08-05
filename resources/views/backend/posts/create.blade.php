@extends('backend.layouts.console')
@section('title', 'Add Post — '.storeName().' Admin')
@section('page_title', 'Add Post')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Content', 'route' => 'admin.posts.index'], ['label' => 'Add Post']]])
@endsection

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="os-card overflow-hidden">
        <div class="border-b border-ink/10 px-6 py-4">
            <h3 class="font-display text-sm font-bold text-ink"><i class="bi bi-plus-circle text-brand"></i> New Post</h3>
        </div>
        <div class="p-6">
            <form action="{{ url('/admin/workers/store') }}" method="post" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="post_title" class="os-label">Name <span class="text-ember">*</span></label>
                        <input type="text" class="os-input w-full" name="title" id="post_title" placeholder="Enter name" required>
                    </div>
                    <div>
                        <label for="post_category" class="os-label">Category <span class="text-ember">*</span></label>
                        <select name="category_id" id="post_category" class="os-input w-full" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="post_contact" class="os-label">Contact Number <span class="text-ember">*</span></label>
                        <input type="number" class="os-input w-full" name="contact_number" id="post_contact" placeholder="Enter contact number" required>
                    </div>
                    <div>
                        <label for="post_division" class="os-label">Division <span class="text-ember">*</span></label>
                        <select name="division" id="post_division" class="os-input w-full" required>
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
                    <div>
                        <label for="post_status" class="os-label">Status <span class="text-ember">*</span></label>
                        <select name="status" id="post_status" class="os-input w-full">
                            <option value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="file" class="os-label">Profile Image <span class="text-ember">*</span></label>
                        <input type="file" class="os-input w-full" name="file" id="file" accept="image/*,video/mp4" required>
                        <div class="mt-3">
                            <img id="preview" src="" class="hidden max-h-40 rounded-xl border border-ink/10" alt="Selected file preview">
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 border-t border-ink/10 pt-5">
                    <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Save</button>
                    <a href="{{ url('admin/workers') }}" class="os-btn os-btn-ghost"><i class="bi bi-arrow-left"></i> Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('file')?.addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    const file = e.target.files[0];
    if (!file) return;
    const allowedImages = ['image/jpeg','image/png','image/gif','image/webp'];
    if (allowedImages.includes(file.type)) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
    } else if (file.type === 'video/mp4') {
        preview.classList.add('hidden');
        window.flash?.(`MP4 video selected: ${file.name}`, 'info');
    } else {
        window.flash?.('Only image and MP4 video files are allowed.', 'error');
        e.target.value = '';
        preview.classList.add('hidden');
    }
});
document.addEventListener('DOMContentLoaded', function () {
    @if(session('success'))
    window.flash?.(@json(session('success')), 'success');
    @endif
    @if(session('error'))
    window.flash?.(@json(session('error')), 'error');
    @endif
    @if ($errors->any())
    window.flash?.(@json($errors->first()), 'error');
    @endif
});
</script>
@endsection
