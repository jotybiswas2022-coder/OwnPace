@extends('backend.layouts.console')
@section('title', 'Orders — '.storeName().' Admin')
@section('page_title', 'Orders')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Orders']]])
@endsection

@section('content')
<div class="os-card overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/10 px-5 py-4">
        <div>
            <h3 class="font-display text-sm font-bold text-ink">All Orders</h3>
            <p class="mt-0.5 text-xs text-slate">{{ $orders->count() ?? 0 }} orders</p>
        </div>
        <a href="{{ route('admin.orders.export') }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-download"></i> Export CSV</a>
    </div>
    <div class="overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr><th>ID</th><th>Customer</th><th>Amount</th><th>Plan</th><th>Status</th><th>Delivery</th><th>Date</th><th class="w-20">View</th></tr>
            </thead>
            <tbody>
                @forelse($orders ?? [] as $o)
                <tr>
                    <td data-label="ID"><span class="font-mono text-sm font-semibold text-ink">#{{ $o->id }}</span></td>
                    <td data-label="Customer" class="text-slate">{{ $o->user?->name ?? 'N/A' }}</td>
                    <td data-label="Amount" class="font-mono font-semibold text-mango-ink">{{ formatPrice($o->total, 0) }}</td>
                    <td data-label="Plan" class="text-slate">{{ $o->installmentPlan?->duration ?? 'N/A' }} {{ $o->installmentPlan?->duration_unit ?? '' }}</td>
                    <td data-label="Status">
                        @php
                            $chip = $o->status == 'completed' ? 'grass' : ($o->status == 'cancelled' ? 'ember' : 'mango');
                        @endphp
                        <span class="os-chip os-chip-{{ $chip }}">{{ ucfirst($o->status) }}</span>
                    </td>
                    <td data-label="Delivery" class="text-slate">{{ ucfirst($o->delivery_status ?? 'pending') }}</td>
                    <td data-label="Date" class="text-xs text-slate">{{ $o->created_at->format('M d, Y') }}</td>
                    <td data-label="View">
                        <a href="{{ route('admin.orders.show', $o) }}" class="os-btn os-btn-ghost os-btn-sm" title="View order"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-14 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-receipt"></i></div>
                        <p class="mt-4 font-semibold text-ink">No orders yet</p>
                        <p class="mt-1 text-sm text-slate">Customer orders will appear here once checkout is used.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
