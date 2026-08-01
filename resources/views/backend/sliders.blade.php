@extends('backend.app')
@section('title', 'Sliders — OwnPace Admin')
@section('page_title', 'Sliders')

@push('styles')
<style>
    .slider-preview {
        width: 160px;
        height: 90px;
        object-fit: cover;
        border-radius: var(--radius-sm);
        border: 1px solid var(--card-border);
    }
    @media (max-width: 576px) {
        .slider-preview { width: 100%; height: auto; max-width: 160px; }
    }
@media (max-width: 768px) {
    .fp-table thead { display: none; }
    .fp-table tbody, .fp-table tr, .fp-table td { display: block; }
    .fp-table tr {
        background: var(--card-dark);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-sm);
        padding: 12px;
        margin-bottom: 12px;
    }
    .fp-table td {
        padding: 8px 0;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }
    .fp-table td:last-child { border-bottom: none; }
    .fp-table td:before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--text-dim);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        flex-shrink: 0;
    }
    .fp-table td:last-child:before { display: none; }
    .fp-table td:last-child { justify-content: flex-end; gap: 6px; }
}
</style>
@endpush

@section('content')

<div class="row g-4">

    <!-- Add New Slider -->
    <div class="col-12 col-lg-5">
        <div class="fp-table-wrap">
            <div class="fp-table-header"><h5><i class="bi bi-plus-circle"></i> Add New Slider</h5></div>
            <div style="padding:24px;">
                <form action="/admin/sliders/store" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:8px;">
                            <i class="bi bi-image" style="color:var(--gold-500);"></i> Slider Image 1
                        </label>
                        <input type="file" accept="image/*" class="fp-form-control" name="slider1" id="slider1" onchange="previewImage(event, 'preview1')">
                        <div class="mt-2 text-center">
                            <img id="preview1" class="slider-preview d-none">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:8px;">
                            <i class="bi bi-image" style="color:var(--gold-500);"></i> Slider Image 2
                        </label>
                        <input type="file" accept="image/*" class="fp-form-control" name="slider2" id="slider2" onchange="previewImage(event, 'preview2')">
                        <div class="mt-2 text-center">
                            <img id="preview2" class="slider-preview d-none">
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="fp-btn fp-btn-gold"><i class="bi bi-check-lg"></i> Add Slider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- All Sliders -->
    <div class="col-12 col-lg-7">
        <div class="fp-table-wrap">
            <div class="fp-table-header"><h5><i class="bi bi-images"></i> All Sliders ({{ $sliders->count() }})</h5></div>
            @if($sliders->count())
            <table class="fp-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Slider 1</th>
                        <th>Slider 2</th>
                        <th>Date</th>
                        <th style="width:80px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sliders as $i => $slider)
                    <tr>
                        <td data-label="#">{{ $loop->iteration }}</td>
                        <td data-label="Slider 1">
                            @if($slider->slider1)
                            <img src="{{ Storage::url($slider->slider1) }}" class="slider-preview" alt="Slider 1">
                            @else
                            <span style="color:var(--text-dim);font-size:12px;">—</span>
                            @endif
                        </td>
                        <td data-label="Slider 2">
                            @if($slider->slider2)
                            <img src="{{ Storage::url($slider->slider2) }}" class="slider-preview" alt="Slider 2">
                            @else
                            <span style="color:var(--text-dim);font-size:12px;">—</span>
                            @endif
                        </td>
                        <td data-label="Date" style="font-size:12px;white-space:nowrap;">{{ $slider->created_at->format('d M Y') }}</td>
                        <td data-label="Action">
                            <button type="button"
                                    class="fp-btn fp-btn-danger btn-sm delete-slider"
                                    data-id="{{ $slider->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-5" style="color:var(--text-dim);">
                <i class="bi bi-images" style="font-size:40px;display:block;margin-bottom:12px;"></i>
                No sliders yet. Add one from the form.
            </div>
            @endif
        </div>
    </div>

</div>

<script>
function previewImage(event, previewId) {
    const preview = document.getElementById(previewId);
    const file = event.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    }
}

document.addEventListener('click', function(e) {
    const btn = e.target.closest('.delete-slider');
    if (!btn) return;
    const id = btn.dataset.id;
    Swal.fire({
        title: 'Delete Slider?',
        text: 'This will permanently delete this slider and its images.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="bi bi-trash"></i> Delete',
        cancelButtonText: '<i class="bi bi-x-lg"></i> Cancel',
        background: '#1A1A1E',
        color: '#F4F4F5',
        iconColor: '#ef4444',
        reverseButtons: true,
        customClass: {
            popup: 'fp-swal-popup',
            confirmButton: 'fp-btn fp-btn-danger',
            cancelButton: 'fp-btn fp-btn-ghost',
        }
    }).then(result => {
        if (result.isConfirmed) {
            window.location.href = '/admin/sliders/delete/' + id;
        }
    });
});
</script>
@endsection