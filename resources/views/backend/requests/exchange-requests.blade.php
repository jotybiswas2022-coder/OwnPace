@extends('backend.layouts.console')
@section('title', 'Exchange Requests — '.storeName().' Admin')
@section('page_title', 'Exchange Requests')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Requests', 'route' => 'admin.requests.index'], ['label' => 'Exchanges']]])
@endsection

@section('content')
<div class="os-card overflow-hidden">
    <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
        <div>
            <h3 class="font-display text-sm font-bold text-ink">Product Exchange Requests</h3>
            <p class="mt-0.5 text-xs text-slate">{{ $requests->count() ?? 0 }} requests</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr><th>Customer</th><th>Current Product</th><th>Wishlist Item</th><th>Reason</th><th>Status</th><th class="w-44">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($requests ?? [] as $r)
                <tr>
                    <td data-label="Customer" class="font-semibold text-ink">{{ $r->user?->name ?? 'N/A' }}</td>
                    <td data-label="Current Product" class="text-slate">{{ $r->current_product?->name ?? 'N/A' }}</td>
                    <td data-label="Wishlist Item" class="text-slate">{{ $r->wishlist_product?->name ?? 'N/A' }}</td>
                    <td data-label="Reason" class="max-w-[200px] text-slate">{{ Str::limit($r->reason, 50) }}</td>
                    <td data-label="Status">
                        <span class="os-chip {{ $r->status == 'approved' ? 'os-chip-grass' : ($r->status == 'rejected' ? 'os-chip-ember' : 'os-chip-mango') }}">{{ ucfirst($r->status) }}</span>
                    </td>
                    <td data-label="Actions">
                        <form action="{{ route('admin.requests.exchange-requests.update', $r) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <label for="status_{{ $r->id }}" class="sr-only">Status</label>
                            <select name="status" id="status_{{ $r->id }}" class="os-input w-auto text-xs">
                                <option value="approved">Approve</option>
                                <option value="rejected">Reject</option>
                                <option value="pending" {{ $r->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                            <button type="submit" class="os-btn os-btn-brand os-btn-sm">Update</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-14 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-arrow-left-right"></i></div>
                        <p class="mt-4 font-semibold text-ink">No exchange requests</p>
                        <p class="mt-1 text-sm text-slate">Customer exchange requests will appear here for review.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
