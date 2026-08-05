@extends('backend.layouts.console')
@section('title', 'Product Requests — '.storeName().' Admin')
@section('page_title', 'Product Requests')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Requests', 'route' => 'admin.requests.index'], ['label' => 'Product Requests']]])
@endsection

@section('content')
<div class="os-card overflow-hidden">
    <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
        <div>
            <h3 class="font-display text-sm font-bold text-ink">New Product Requests</h3>
            <p class="mt-0.5 text-xs text-slate">{{ $requests->count() ?? 0 }} requests</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr><th>Customer</th><th>Product Name</th><th>Details</th><th>Date</th><th>Status</th><th>Review</th></tr>
            </thead>
            <tbody>
                @forelse($requests ?? [] as $r)
                <tr>
                    <td data-label="Customer" class="font-semibold text-ink">{{ $r->user?->name ?? 'N/A' }}</td>
                    <td data-label="Product Name" class="text-ink">{{ $r->product_name }}</td>
                    <td data-label="Details" class="max-w-[220px] text-slate">
                        @if($r->product_url)
                            <a href="{{ $r->product_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-medium text-brand hover:underline"><i class="bi bi-box-arrow-up-right"></i> Link</a>
                        @endif
                        @if($r->reason)
                        <p class="mt-1 text-xs text-slate">Why: {{ Str::limit($r->reason, 60) }}</p>
                        @endif
                    </td>
                    <td data-label="Date" class="text-xs text-slate">{{ $r->created_at->format('M d, Y') }}</td>
                    <td data-label="Status">
                        <span class="os-chip {{ $r->status == 'approved' ? 'os-chip-grass' : ($r->status == 'rejected' ? 'os-chip-ember' : 'os-chip-mango') }}">{{ ucfirst(str_replace('_', ' ', $r->status)) }}</span>
                    </td>
                    <td data-label="Review">
                        <form action="{{ route('admin.requests.product-requests.update', $r) }}" method="POST" class="flex flex-wrap items-center gap-2">
                            @csrf
                            <label for="status_{{ $r->id }}" class="sr-only">Status</label>
                            <select name="status" id="status_{{ $r->id }}" class="os-input w-auto text-xs">
                                <option value="under_review" {{ $r->status == 'under_review' ? 'selected' : '' }}>Under review</option>
                                <option value="approved" {{ $r->status == 'approved' ? 'selected' : '' }}>Approve</option>
                                <option value="rejected" {{ $r->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                                <option value="submitted" {{ $r->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            </select>
                            <label for="notes_{{ $r->id }}" class="sr-only">Note</label>
                            <input type="text" name="admin_notes" id="notes_{{ $r->id }}" placeholder="Note" class="os-input w-32 text-xs" value="{{ $r->admin_notes }}">
                            <button type="submit" class="os-btn os-btn-brand os-btn-sm">Update</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-14 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-bag-plus"></i></div>
                        <p class="mt-4 font-semibold text-ink">No product requests</p>
                        <p class="mt-1 text-sm text-slate">Requests for products customers want will show up here.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
