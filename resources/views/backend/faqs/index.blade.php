@extends('backend.app')
@section('title', 'FAQs — OwnPace Admin')
@section('page_title', 'FAQs')

@push('styles')
<style>
    .faq-textarea { min-height: 100px; resize: vertical; }
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
    .fp-table .empty-row td:before { display: none; }
    .fp-table .empty-row td { justify-content: center; }
}
</style>
@endpush

@section('content')
<div class="fp-table-wrap mb-4">
    <div class="fp-table-header"><h5>Add New FAQ</h5></div>
    <div class="p-3">
        <form action="{{ route('admin.faqs.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="question" class="fp-form-control" placeholder="Question" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="category" class="fp-form-control" placeholder="Category (optional)">
                </div>
                <div class="col-md-3">
                    <input type="number" name="sort_order" class="fp-form-control" placeholder="Sort Order" value="0" min="0">
                </div>
                <div class="col-12">
                    <textarea name="answer" class="fp-form-control faq-textarea" placeholder="Answer" required></textarea>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" class="form-check-input" id="new_faq_active" value="1" checked style="accent-color:var(--gold-500);">
                        <label for="new_faq_active" style="color:var(--text-muted);font-size:13px;">Active</label>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="fp-btn fp-btn-gold">Add FAQ</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>All FAQs</h5></div>
    <table class="fp-table">
        <thead><tr><th>Question</th><th>Category</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($faqs ?? [] as $faq)
            <tr>
                <td data-label="Question">
                    <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
                        @csrf
                        <input type="text" name="question" class="fp-form-control" value="{{ $faq->question }}" style="width:250px;padding:6px 10px;" required>
                </td>
                <td data-label="Category">
                    <input type="text" name="category" class="fp-form-control" value="{{ $faq->category }}" style="width:130px;padding:6px 10px;">
                </td>
                <td data-label="Order">
                    <input type="number" name="sort_order" class="fp-form-control" value="{{ $faq->sort_order }}" style="width:70px;padding:6px 10px;" min="0">
                </td>
                <td data-label="Status">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" class="form-check-input" id="active_{{ $faq->id }}" value="1" {{ $faq->is_active ? 'checked' : '' }} style="accent-color:var(--gold-500);">
                </td>
                <td data-label="Actions">
                    <div class="d-flex gap-1">
                        <button type="submit" class="fp-btn fp-btn-gold" style="padding:6px 12px;">Save</button>
                    </form>
                    <a href="{{ route('admin.faqs.delete', $faq) }}" class="fp-btn fp-btn-danger" style="padding:6px 12px;" onclick="return confirm('Delete this FAQ?')">Delete</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="5" class="text-center py-4" style="color:var(--text-dim);">No FAQs yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
