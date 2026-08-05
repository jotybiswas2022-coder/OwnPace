@extends('backend.layouts.console')
@section('title', 'FAQs — '.storeName().' Admin')
@section('page_title', 'FAQs')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Content'], ['label' => 'FAQs']]])
@endsection

@section('content')
<div class="space-y-6">
    {{-- Add New FAQ --}}
    <div class="os-card overflow-hidden">
        <div class="border-b border-ink/10 px-6 py-4">
            <h3 class="font-display text-sm font-bold text-ink">Add New FAQ</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.faqs.store') }}" method="POST" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @csrf
                <div>
                    <label for="question" class="os-label">Question <span class="text-ember">*</span></label>
                    <input type="text" id="question" name="question" class="os-input w-full" placeholder="Question" required>
                </div>
                <div>
                    <label for="category" class="os-label">Category (optional)</label>
                    <input type="text" id="category" name="category" class="os-input w-full" placeholder="e.g. Payments">
                </div>
                <div>
                    <label for="sort_order" class="os-label">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" class="os-input w-full" placeholder="Sort Order" value="0" min="0">
                </div>
                <div class="sm:col-span-2 lg:col-span-3">
                    <label for="answer" class="os-label">Answer <span class="text-ember">*</span></label>
                    <textarea id="answer" name="answer" class="os-input w-full" rows="3" placeholder="Answer" required></textarea>
                </div>
                <div class="flex items-center gap-3 sm:col-span-2 lg:col-span-3">
                    <label class="flex cursor-pointer items-center gap-2 text-sm font-medium text-ink">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 rounded border-ink/20 accent-brand">
                        Active
                    </label>
                    <button type="submit" class="os-btn os-btn-brand os-btn-sm ml-auto"><i class="bi bi-plus-lg"></i> Add FAQ</button>
                </div>
            </form>
        </div>
    </div>

    {{-- All FAQs --}}
    <div class="os-card overflow-hidden">
        <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-display text-sm font-bold text-ink">All FAQs</h3>
            <p class="mt-0.5 text-xs text-slate">{{ $faqs->count() ?? 0 }} FAQs</p>
        </div>
        <div class="overflow-x-auto">
            <table class="os-table w-full">
                <thead>
                    <tr><th>Question</th><th>Category</th><th>Order</th><th>Status</th><th class="w-28">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($faqs ?? [] as $faq)
                    <tr>
                        <td data-label="Question">
                            <label for="question_{{ $faq->id }}" class="sr-only">Question</label>
                            <input type="text" name="question" id="question_{{ $faq->id }}" form="faq-update-{{ $faq->id }}" class="os-input min-w-56 text-sm" value="{{ $faq->question }}" required>
                        </td>
                        <td data-label="Category">
                            <label for="category_{{ $faq->id }}" class="sr-only">Category</label>
                            <input type="text" name="category" id="category_{{ $faq->id }}" form="faq-update-{{ $faq->id }}" class="os-input w-32 text-sm" value="{{ $faq->category }}">
                        </td>
                        <td data-label="Order">
                            <label for="sort_{{ $faq->id }}" class="sr-only">Sort order</label>
                            <input type="number" name="sort_order" id="sort_{{ $faq->id }}" form="faq-update-{{ $faq->id }}" class="os-input w-20 text-sm" value="{{ $faq->sort_order }}" min="0">
                        </td>
                        <td data-label="Status">
                            <label class="flex items-center gap-2 text-sm font-medium text-ink">
                                <input type="hidden" name="is_active" value="0" form="faq-update-{{ $faq->id }}">
                                <input type="checkbox" name="is_active" id="active_{{ $faq->id }}" value="1" {{ $faq->is_active ? 'checked' : '' }} form="faq-update-{{ $faq->id }}" class="h-4 w-4 rounded border-ink/20 accent-brand">
                                Active
                            </label>
                        </td>
                        <td data-label="Actions">
                            <div class="flex items-center gap-2">
                                <form id="faq-update-{{ $faq->id }}" action="{{ route('admin.faqs.update', $faq) }}" method="POST">
                                    @csrf
                                </form>
                                <button type="submit" form="faq-update-{{ $faq->id }}" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-check-lg"></i> Save</button>
                                <a href="{{ route('admin.faqs.delete', $faq) }}" class="os-btn os-btn-danger os-btn-sm" onclick="return confirm('Delete this FAQ?')"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-14 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-question-circle"></i></div>
                            <p class="mt-4 font-semibold text-ink">No FAQs yet</p>
                            <p class="mt-1 text-sm text-slate">Add an FAQ above to answer common customer questions.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
