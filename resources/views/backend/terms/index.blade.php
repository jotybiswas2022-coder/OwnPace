@extends('backend.app')
@section('title', 'Terms & Conditions — OwnPace Admin')
@section('page_title', 'Terms & Conditions')

@push('styles')
<style>
    .terms-textarea { min-height: 300px; resize: vertical; font-family: monospace; }
</style>
@endpush

@section('content')
<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>Manage Terms & Conditions</h5></div>
    <div class="p-3">
        @forelse($terms ?? [] as $term)
        <div class="mb-4" style="border-bottom:1px solid var(--card-border);padding-bottom:20px;">
            <form action="{{ route('admin.terms.update', $term) }}" method="POST">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-sm-8">
                        <label style="color:var(--text-dim);font-size:12px;display:block;margin-bottom:4px;">Title</label>
                        <input type="text" name="title" class="fp-form-control" value="{{ $term->title }}" required>
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <label style="color:var(--text-dim);font-size:12px;display:block;margin-bottom:4px;">Type</label>
                        <input type="text" class="fp-form-control" value="{{ $term->type }}" disabled style="opacity:0.6;">
                    </div>
                    <div class="col-sm-6 col-md-2 d-flex align-items-end">
                        <div class="form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" class="form-check-input" id="active_{{ $term->id }}" value="1" {{ $term->is_active ? 'checked' : '' }} style="accent-color:var(--gold-500);">
                            <label for="active_{{ $term->id }}" style="color:var(--text-muted);font-size:13px;">Active</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label style="color:var(--text-dim);font-size:12px;display:block;margin-bottom:4px;">Content</label>
                    <textarea name="content" class="fp-form-control terms-textarea" required>{{ $term->content }}</textarea>
                </div>
                <button type="submit" class="fp-btn fp-btn-gold">Update</button>
            </form>
        </div>
        @empty
        <p style="color:var(--text-dim);text-align:center;padding:40px;">No terms configured</p>
        @endforelse
    </div>
</div>
@endsection
