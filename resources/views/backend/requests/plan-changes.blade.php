@extends('backend.layouts.console')
@section('title', 'Plan Changes — '.storeName().' Admin')
@section('page_title', 'Plan Change Requests')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Requests', 'route' => 'admin.requests.index'], ['label' => 'Plan Changes']]])
@endsection

@section('content')
<div class="os-card overflow-hidden">
    <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
        <div>
            <h3 class="font-display text-sm font-bold text-ink">Plan Change Requests</h3>
            <p class="mt-0.5 text-xs text-slate">{{ $requests->count() ?? 0 }} requests</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr><th>User</th><th>Order</th><th>Current Plan</th><th>Reason</th><th>Status</th><th class="w-44">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($requests ?? [] as $r)
                <tr>
                    <td data-label="User" class="font-semibold text-ink">{{ $r->user?->name ?? 'N/A' }}</td>
                    <td data-label="Order" class="font-mono text-xs text-slate">#{{ $r->order_id }}</td>
                    <td data-label="Current Plan" class="text-slate">{{ $r->current_plan ?? 'N/A' }}</td>
                    <td data-label="Reason" class="max-w-[200px] text-slate">{{ Str::limit($r->reason, 50) }}</td>
                    <td data-label="Status">
                        <span class="os-chip {{ $r->status == 'approved' ? 'os-chip-grass' : ($r->status == 'rejected' ? 'os-chip-ember' : 'os-chip-mango') }}">{{ ucfirst($r->status) }}</span>
                    </td>
                    <td data-label="Actions">
                        @if($r->status === 'pending')
                        <div class="flex items-center gap-2">
                            <form action="{{ route('admin.requests.plan-changes.approve', $r) }}" method="POST">
                                @csrf
                                <button type="submit" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-check-lg"></i> Approve</button>
                            </form>
                            <form action="{{ route('admin.requests.plan-changes.reject', $r) }}" method="POST">
                                @csrf
                                <button type="submit" class="os-btn os-btn-danger os-btn-sm"><i class="bi bi-x-lg"></i> Reject</button>
                            </form>
                        </div>
                        @else
                        <span class="text-xs text-slate">Processed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-14 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-arrow-repeat"></i></div>
                        <p class="mt-4 font-semibold text-ink">No plan change requests</p>
                        <p class="mt-1 text-sm text-slate">Requests to switch installment plans will appear here.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
