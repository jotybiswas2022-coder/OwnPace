@extends('backend.layouts.console')
@section('title', 'Sliders — '.storeName().' Admin')
@section('page_title', 'Sliders')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Content'], ['label' => 'Sliders']]])
@endsection

@section('content')
<div class="grid gap-6 lg:grid-cols-5">
    {{-- Add New Slider --}}
    <div class="lg:col-span-2">
        <div class="os-card overflow-hidden">
            <div class="border-b border-ink/10 px-6 py-4">
                <h3 class="font-display text-sm font-bold text-ink"><i class="bi bi-plus-circle text-brand"></i> Add New Slider</h3>
            </div>
            <div class="p-6">
                <form action="/admin/sliders/store" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label for="slider1" class="os-label"><i class="bi bi-image mr-1 text-mango-deep"></i> Slider Image 1</label>
                        <input type="file" accept="image/*" class="os-input w-full" name="slider1" id="slider1" onchange="previewImage(event, 'preview1')">
                        <div class="mt-3 flex justify-center">
                            <img id="preview1" class="hidden max-h-28 rounded-xl border border-ink/10 object-cover" alt="Slider 1 preview">
                        </div>
                    </div>
                    <div>
                        <label for="slider2" class="os-label"><i class="bi bi-image mr-1 text-mango-deep"></i> Slider Image 2</label>
                        <input type="file" accept="image/*" class="os-input w-full" name="slider2" id="slider2" onchange="previewImage(event, 'preview2')">
                        <div class="mt-3 flex justify-center">
                            <img id="preview2" class="hidden max-h-28 rounded-xl border border-ink/10 object-cover" alt="Slider 2 preview">
                        </div>
                    </div>
                    <button type="submit" class="os-btn os-btn-brand w-full"><i class="bi bi-check-lg"></i> Add Slider</button>
                </form>
            </div>
        </div>
    </div>

    {{-- All Sliders --}}
    <div class="lg:col-span-3">
        <div class="os-card overflow-hidden">
            <div class="border-b border-ink/10 px-6 py-4">
                <h3 class="font-display text-sm font-bold text-ink"><i class="bi bi-images text-brand"></i> All Sliders ({{ $sliders->count() }})</h3>
            </div>
            @if($sliders->count())
            <div class="overflow-x-auto">
                <table class="os-table w-full">
                    <thead>
                        <tr><th>#</th><th>Slider 1</th><th>Slider 2</th><th>Date</th><th class="w-20">Action</th></tr>
                    </thead>
                    <tbody>
                        @foreach($sliders as $slider)
                        <tr>
                            <td data-label="#" class="text-slate">{{ $loop->iteration }}</td>
                            <td data-label="Slider 1">
                                @if($slider->slider1)
                                <img src="{{ Storage::url($slider->slider1) }}" alt="Slider 1" class="h-16 w-28 rounded-lg border border-ink/10 object-cover">
                                @else
                                <span class="text-xs text-slate">—</span>
                                @endif
                            </td>
                            <td data-label="Slider 2">
                                @if($slider->slider2)
                                <img src="{{ Storage::url($slider->slider2) }}" alt="Slider 2" class="h-16 w-28 rounded-lg border border-ink/10 object-cover">
                                @else
                                <span class="text-xs text-slate">—</span>
                                @endif
                            </td>
                            <td data-label="Date" class="whitespace-nowrap text-xs text-slate">{{ $slider->created_at->format('d M Y') }}</td>
                            <td data-label="Action">
                                <div x-data>
                                    <button type="button" class="os-btn os-btn-danger os-btn-sm" @click="$refs.confirmDel.showModal()" aria-label="Delete slider"><i class="bi bi-trash"></i></button>

                                    <dialog x-ref="confirmDel" class="m-auto rounded-2xl border border-ink/10 bg-white p-0 shadow-2xl backdrop:bg-ink/40">
                                        <div class="p-6">
                                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-ember/10 text-xl text-ember"><i class="bi bi-exclamation-triangle"></i></div>
                                            <h4 class="mt-4 text-center font-display text-base font-bold text-ink">Delete Slider?</h4>
                                            <p class="mt-1 text-center text-sm text-slate">This permanently deletes this slider and its images.</p>
                                            <div class="mt-5 flex justify-center gap-3">
                                                <button type="button" class="os-btn os-btn-ghost" @click="$refs.confirmDel.close()">Cancel</button>
                                                <a href="/admin/sliders/delete/{{ $slider->id }}" class="os-btn os-btn-danger"><i class="bi bi-trash"></i> Delete</a>
                                            </div>
                                        </div>
                                    </dialog>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="py-14 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-images"></i></div>
                <p class="mt-4 font-semibold text-ink">No sliders yet</p>
                <p class="mt-1 text-sm text-slate">Add a slider from the form to feature banners on the storefront.</p>
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
        preview.classList.remove('hidden');
    }
}
</script>
@endsection
