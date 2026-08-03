@extends('backend.layouts.console')
@section('title', 'Dashboard — '.storeName().' Admin')
@section('page_title', 'Dashboard')

@section('content')

<!-- Welcome strip -->
<div class="os-card mb-6 flex flex-wrap items-center justify-between gap-4 p-5">
    <div class="flex items-center gap-4">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-mango/15 text-xl text-mango-deep"><i class="bi bi-lightning-charge-fill"></i></span>
        <div>
            <h2 class="font-display text-base font-bold text-ink">Good {{ now()->format('H') < 12 ? 'Morning' : (now()->format('H') < 17 ? 'Afternoon' : 'Evening') }}, {{ auth()->user()->name ?? 'Admin' }}</h2>
            <p class="text-sm text-slate">Here's what's happening with your store today.</p>
        </div>
    </div>
    <span class="os-chip os-chip-brand"><i class="bi bi-calendar3"></i> {{ now()->format('l, F j, Y') }}</span>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
    @php
        $stats = [
            ['icon' => 'bi-currency-exchange', 'label' => 'Total Revenue', 'value' => formatPrice($totalRevenue ?? 0, 0)],
            ['icon' => 'bi-receipt', 'label' => 'Total Orders', 'value' => number_format($totalOrders ?? 0)],
            ['icon' => 'bi-people-fill', 'label' => 'Customers', 'value' => number_format($totalUsers ?? 0)],
            ['icon' => 'bi-box-seam-fill', 'label' => 'Products', 'value' => number_format($totalProducts ?? 0)],
        ];
    @endphp
    @foreach($stats as $stat)
    <div class="os-card os-card-hover p-5">
        <div class="flex items-center justify-between">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand/10 text-lg text-brand"><i class="bi {{ $stat['icon'] }}"></i></span>
        </div>
        <p class="mt-4 font-mono text-2xl font-semibold tracking-tight text-ink">{{ $stat['value'] }}</p>
        <p class="mt-1 text-xs font-medium text-slate">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>

<!-- Plan lifecycle -->
<div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @php
        $lifecycle = [
            ['icon' => 'bi-play-circle-fill', 'label' => 'Active Plans', 'value' => number_format($activePlans ?? 0), 'icon_bg' => 'bg-brand/10 text-brand'],
            ['icon' => 'bi-check-circle-fill', 'label' => 'Completed Plans', 'value' => number_format($completedPlans ?? 0), 'icon_bg' => 'bg-grass/10 text-grass'],
            ['icon' => 'bi-x-circle-fill', 'label' => 'Cancelled Plans', 'value' => number_format($cancelledPlans ?? 0), 'icon_bg' => 'bg-ember/10 text-ember'],
            ['icon' => 'bi-exclamation-triangle-fill', 'label' => 'Overdue Installments', 'value' => number_format($overduePayments ?? 0), 'icon_bg' => 'bg-mango/15 text-mango-deep', 'sub' => '₦'.number_format($dueThisMonth ?? 0, 0).' due this month'],
        ];
    @endphp
    @foreach($lifecycle as $card)
    <div class="os-card os-card-hover p-5">
        <div class="flex items-center justify-between">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl text-lg {{ $card['icon_bg'] }}"><i class="bi {{ $card['icon'] }}"></i></span>
        </div>
        <p class="mt-4 font-mono text-2xl font-semibold tracking-tight text-ink">{{ $card['value'] }}</p>
        <p class="mt-1 text-xs font-medium text-slate">{{ $card['label'] }}</p>
        @if(!empty($card['sub']))
        <p class="mt-0.5 text-[11px] text-slate/70">{{ $card['sub'] }}</p>
        @endif
    </div>
    @endforeach
</div>

<!-- Pending requests -->
<div class="mt-6 grid gap-4 md:grid-cols-3">
    @php
        $requests = [
            ['route' => 'admin.requests.index', 'icon' => 'bi-arrow-repeat', 'label' => 'Pending Plan Changes', 'count' => $pendingPlanChanges ?? 0],
            ['route' => 'admin.requests.index', 'icon' => 'bi-plus-circle', 'label' => 'Product Requests', 'count' => $pendingProductRequests ?? 0],
            ['route' => 'admin.requests.index', 'icon' => 'bi-arrow-left-right', 'label' => 'Exchange Requests', 'count' => $pendingExchanges ?? 0],
        ];
    @endphp
    @foreach($requests as $req)
    <a href="{{ route($req['route']) }}" class="os-card os-card-hover flex items-center gap-4 p-5">
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand/10 text-xl text-brand"><i class="bi {{ $req['icon'] }}"></i></span>
        <div class="min-w-0 flex-1">
            <p class="text-xs text-slate">{{ $req['label'] }}</p>
            <p class="font-mono text-2xl font-semibold text-ink">{{ $req['count'] }}</p>
        </div>
        <i class="bi bi-chevron-right text-slate"></i>
    </a>
    @endforeach
</div>

<!-- Recent activity -->
<div class="mt-6 grid gap-6 lg:grid-cols-2">

    <!-- Recent orders -->
    <div class="os-card overflow-hidden">
        <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
            <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-clock-history text-mango-deep"></i> Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-brand hover:underline">View all</a>
        </div>
        <div class="divide-y divide-ink/5">
            @forelse($recentOrders ?? [] as $o)
            <div class="flex items-center gap-4 px-5 py-3.5">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand/10 font-display text-sm font-bold text-brand">{{ strtoupper(substr($o->user?->name ?? '?', 0, 1)) }}</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-ink">#{{ $o->id }} · {{ $o->user?->name ?? 'N/A' }}</p>
                    <p class="text-xs text-slate">{{ $o->created_at->diffForHumans() }}</p>
                </div>
                <span class="font-mono text-sm font-semibold text-ink">{{ formatPrice($o->grand_total ?? 0, 0) }}</span>
                <span class="os-chip os-chip-{{ in_array($o->status, ['completed','fully_paid']) ? 'grass' : (in_array($o->status, ['pending','partial_paid']) ? 'mango' : (in_array($o->status, ['cancelled','failed']) ? 'ember' : 'brand')) }}">{{ ucwords(str_replace('_', ' ', $o->status)) }}</span>
            </div>
            @empty
            <p class="px-5 py-10 text-center text-sm text-slate"><i class="bi bi-inbox me-1"></i> No orders yet</p>
            @endforelse
        </div>
    </div>

    <!-- New customers -->
    <div class="os-card overflow-hidden">
        <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
            <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-person-plus-fill text-mango-deep"></i> New Customers</h3>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-brand hover:underline">View all</a>
        </div>
        <div class="divide-y divide-ink/5">
            @forelse($recentUsers ?? [] as $u)
            <div class="flex items-center gap-4 px-5 py-3.5">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand/10 font-display text-sm font-bold text-brand">{{ strtoupper(substr($u->name ?? '?', 0, 1)) }}</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-ink">{{ $u->name ?? 'N/A' }}</p>
                    <p class="truncate text-xs text-slate">{{ $u->email }}</p>
                </div>
                <span class="text-xs text-slate">{{ $u->created_at->diffForHumans() }}</span>
            </div>
            @empty
            <p class="px-5 py-10 text-center text-sm text-slate"><i class="bi bi-people me-1"></i> No users yet</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent transactions -->
<div class="os-card mt-6 overflow-hidden">
    <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
        <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-credit-card-2-front text-mango-deep"></i> Recent Transactions</h3>
        <a href="{{ route('admin.transactions.index') }}" class="text-xs font-semibold text-brand hover:underline">View all</a>
    </div>
    <div class="divide-y divide-ink/5">
        @forelse($recentTransactions ?? [] as $t)
        <div class="flex items-center gap-4 px-5 py-3.5">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand/10 font-display text-sm font-bold text-brand">{{ strtoupper(substr($t->user?->name ?? '?', 0, 1)) }}</span>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-ink">{{ $t->user?->name ?? 'N/A' }}</p>
                <p class="truncate font-mono text-xs text-slate">{{ $t->transaction_reference }}</p>
            </div>
            <span class="hidden text-xs text-slate sm:block">{{ $t->gateway }}</span>
            <span class="font-mono text-sm font-semibold text-ink">{{ formatPrice($t->amount ?? 0, 0) }}</span>
            <span class="os-chip os-chip-{{ $t->status === 'success' ? 'grass' : ($t->status === 'failed' ? 'ember' : 'brand') }}">{{ ucfirst($t->status) }}</span>
        </div>
        @empty
        <p class="px-5 py-10 text-center text-sm text-slate"><i class="bi bi-inbox me-1"></i> No transactions yet</p>
        @endforelse
    </div>
</div>

<!-- Quick actions -->
<div class="os-card mt-6 p-5">
    <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-lightning-fill text-mango-deep"></i> Quick Actions</h3>
    <div class="mt-4 flex flex-wrap gap-2.5">
        @php
            $actions = [
                ['route' => 'admin.products.create', 'icon' => 'bi-plus-lg', 'label' => 'Add Product'],
                ['route' => 'admin.category.create', 'icon' => 'bi-tag', 'label' => 'New Category'],
                ['route' => 'admin.brands.create', 'icon' => 'bi-building', 'label' => 'Add Brand'],
                ['route' => 'admin.suppliers.create', 'icon' => 'bi-truck', 'label' => 'New Supplier'],
                ['route' => 'admin.campaigns.create', 'icon' => 'bi-megaphone', 'label' => 'Create Campaign'],
                ['route' => 'admin.analytics', 'icon' => 'bi-graph-up', 'label' => 'View Analytics'],
                ['route' => 'admin.settings', 'icon' => 'bi-gear', 'label' => 'Settings'],
                ['route' => 'admin.orders.export', 'icon' => 'bi-download', 'label' => 'Export Orders'],
            ];
        @endphp
        @foreach($actions as $action)
        <a href="{{ route($action['route']) }}" class="inline-flex items-center gap-2 rounded-lg border border-ink/15 bg-white px-3.5 py-2 text-xs font-semibold text-ink transition-colors hover:border-mango hover:bg-mango/5">
            <i class="bi {{ $action['icon'] }}"></i> {{ $action['label'] }}
        </a>
        @endforeach
    </div>
</div>

@endsection
