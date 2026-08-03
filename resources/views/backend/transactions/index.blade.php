@extends('backend.layouts.console')
@section('title', 'Transactions — '.storeName().' Admin')
@section('page_title', 'Transaction History')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Transactions']]])
@endsection

@section('content')

<!-- Summary -->
<div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="os-card p-5">
        <p class="text-xs font-medium text-slate">Collected (paid)</p>
        <p class="mt-2 font-mono text-2xl font-semibold tracking-tight text-grass">{{ formatPrice($summary['total_collected'] ?? 0, 0) }}</p>
    </div>
    <div class="os-card p-5">
        <p class="text-xs font-medium text-slate">Pending due</p>
        <p class="mt-2 font-mono text-2xl font-semibold tracking-tight text-mango-deep">{{ formatPrice($summary['pending_due'] ?? 0, 0) }}</p>
    </div>
    <div class="os-card p-5">
        <p class="text-xs font-medium text-slate">Overdue installments</p>
        <p class="mt-2 font-mono text-2xl font-semibold tracking-tight text-ember">{{ number_format($summary['overdue'] ?? 0) }}</p>
    </div>
</div>

<!-- Filters -->
<div class="os-card mt-6 p-5">
    <form method="GET" action="{{ route('admin.transactions.index') }}" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-6">
        <div class="lg:col-span-2">
            <label class="mb-1.5 block text-xs font-semibold text-slate">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" class="os-input w-full" placeholder="Order #, customer name or email…">
        </div>
        <div>
            <label class="mb-1.5 block text-xs font-semibold text-slate">Status</label>
            <select name="status" class="os-input w-full">
                <option value="">All</option>
                @foreach(['pending', 'paid', 'overdue', 'partial'] as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-xs font-semibold text-slate">Method</label>
            <select name="method" class="os-input w-full">
                <option value="">All</option>
                @foreach(['wallet', 'paystack', 'flutterwave', 'korapay'] as $m)
                    <option value="{{ $m }}" {{ request('method') === $m ? 'selected' : '' }}>{{ ucfirst($m) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-xs font-semibold text-slate">Due from</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="os-input w-full">
        </div>
        <div>
            <label class="mb-1.5 block text-xs font-semibold text-slate">Due to</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="os-input w-full">
        </div>
        <div class="sm:col-span-2 lg:col-span-3 flex items-end gap-2">
            <select name="sort" class="os-input w-auto">
                <option value="">Sort: newest due</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest due</option>
                <option value="amount_desc" {{ request('sort') === 'amount_desc' ? 'selected' : '' }}>Amount: high → low</option>
                <option value="amount_asc" {{ request('sort') === 'amount_asc' ? 'selected' : '' }}>Amount: low → high</option>
            </select>
            <button type="submit" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-funnel-fill"></i> Filter</button>
            @if(request()->has('search') || request()->has('status') || request()->has('method') || request()->has('date_from') || request()->has('date_to'))
                <a href="{{ route('admin.transactions.index') }}" class="os-btn os-btn-ghost os-btn-sm text-ember"><i class="bi bi-x-lg"></i></a>
            @endif
            <a href="{{ route('admin.transactions.export', request()->query()) }}" class="os-btn os-btn-ghost os-btn-sm ml-auto"><i class="bi bi-download"></i> Export CSV</a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="os-card mt-6 overflow-hidden">
    <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
        <h3 class="font-display text-sm font-bold text-ink">Installment payments</h3>
        <span class="text-xs text-slate">{{ $payments->total() }} total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr>
                    <th>Installment</th>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Paid</th>
                    <th>Due date</th>
                    <th>Paid date</th>
                    <th>Status</th>
                    <th>Method</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                <tr>
                    <td class="font-mono text-sm text-ink">#{{ $p->installment_number }}</td>
                    <td class="font-mono text-xs text-slate">{{ $p->order?->order_number ?? '—' }}</td>
                    <td>
                        <p class="text-sm font-semibold text-ink">{{ $p->order?->user?->name ?? 'N/A' }}</p>
                        <p class="text-xs text-slate">{{ $p->order?->user?->email }}</p>
                    </td>
                    <td>
                        @if($p->order?->installmentPlan)
                            <span class="os-chip">{{ $p->order->installmentPlan->name }}</span>
                        @else
                            <span class="text-xs text-slate">—</span>
                        @endif
                    </td>
                    <td class="font-mono text-sm font-semibold text-ink">{{ formatPrice($p->amount, 0) }}</td>
                    <td class="font-mono text-sm text-grass">{{ $p->paid_amount > 0 ? formatPrice($p->paid_amount, 0) : '—' }}</td>
                    <td class="text-sm text-slate">{{ $p->due_date?->format('M j, Y') ?? '—' }}</td>
                    <td class="text-sm text-slate">{{ $p->paid_date?->format('M j, Y') ?? '—' }}</td>
                    <td>
                        @php
                            $chip = $p->status === 'paid' ? 'grass' : ($p->status === 'overdue' ? 'ember' : ($p->status === 'partial' ? 'mango' : 'brand'));
                        @endphp
                        <span class="os-chip os-chip-{{ $chip }}">{{ ucfirst($p->status) }}</span>
                        @if((float) $p->late_fee > 0)
                            <p class="mt-1 text-[11px] text-ember">+{{ formatPrice($p->late_fee, 0) }} late fee</p>
                        @endif
                    </td>
                    <td class="text-xs text-slate">{{ ucfirst($p->payment_method ?? '—') }}</td>
                </tr>
                @empty
                <tr><td colspan="10" class="py-8 text-center text-sm text-slate">No installment payments match your filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="border-t border-ink/10 px-5 py-4">
        {{ $payments->links() }}
    </div>
</div>

@endsection
