@extends('backend.layouts.console')
@section('title', 'Dashboard — '.storeName().' Admin')
@section('page_title', 'Dashboard')

@section('content')

<style>
    /* Dashboard-specific polish */
    .dash-hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(620px 300px at 92% -20%, rgba(245, 166, 35, 0.3), transparent 62%),
            radial-gradient(480px 320px at 0% 125%, rgba(124, 105, 255, 0.4), transparent 58%),
            linear-gradient(135deg, #211e52 0%, #2e2a6b 58%, #4a4599 100%);
        border-radius: 1.25rem;
        box-shadow: 0 30px 60px -30px rgba(46, 42, 107, 0.65);
    }
    .dash-hero::after {
        content: "";
        position: absolute;
        top: -70%;
        right: -6%;
        width: 45%;
        height: 240%;
        background: radial-gradient(circle at 50% 50%, rgba(245, 166, 35, 0.22), transparent 62%);
        pointer-events: none;
    }
    .dash-hero .dash-greet {
        font-family: var(--font-display);
        font-weight: 700;
        letter-spacing: -0.02em;
        color: #fff;
    }
    .dash-hero .dash-sub {
        color: rgba(255, 255, 255, 0.72);
    }
    .dash-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 0.95rem;
        border-radius: 9999px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #fff;
        background: rgba(255, 255, 255, 0.12);
        -webkit-backdrop-filter: blur(10px);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.22);
    }
    .dash-chip i { color: #ffd88f; }

    .stat-tile {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.9rem;
        height: 2.9rem;
        border-radius: 0.95rem;
        font-size: 1.15rem;
    }
    .tile-gold   { background: linear-gradient(135deg, #ffd88f, #f5a623); color: #16131c; box-shadow: 0 12px 26px -12px rgba(245, 166, 35, 0.85); }
    .tile-indigo { background: linear-gradient(135deg, #4a4599, #2e2a6b); color: #fff; box-shadow: 0 12px 26px -12px rgba(46, 42, 107, 0.7); }
    .tile-grass  { background: linear-gradient(135deg, #4ade80, #2f9e44); color: #fff; box-shadow: 0 12px 26px -12px rgba(47, 158, 68, 0.65); }
    .tile-ember  { background: linear-gradient(135deg, #f87171, #e0483e); color: #fff; box-shadow: 0 12px 26px -12px rgba(224, 72, 62, 0.65); }

    .stat-card .stat-value {
        font-family: var(--font-mono);
        font-variant-numeric: tabular-nums;
        font-size: 1.55rem;
        font-weight: 600;
        letter-spacing: -0.02em;
        color: var(--ink);
    }
    .stat-card .stat-label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--slate);
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        font-family: var(--font-display);
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--ink);
    }
    .section-title i { color: var(--mango-deep); }

    @media (prefers-reduced-motion: reduce) {
        .dash-hero::after, .stat-tile { animation: none; }
    }
</style>

<!-- Welcome hero -->
<div class="dash-hero mb-6 flex flex-wrap items-center justify-between gap-4 p-6 sm:p-7">
    <div class="flex items-center gap-4">
        <span class="stat-tile tile-gold"><i class="bi bi-lightning-charge-fill"></i></span>
        <div>
            <h2 class="dash-greet text-lg sm:text-xl">Good {{ now()->format('H') < 12 ? 'Morning' : (now()->format('H') < 17 ? 'Afternoon' : 'Evening') }}, {{ auth()->user()->name ?? 'Admin' }}</h2>
            <p class="dash-sub mt-0.5 text-sm">Here's what's happening with your store today.</p>
        </div>
    </div>
    <span class="dash-chip"><i class="bi bi-calendar3"></i> {{ now()->format('l, F j, Y') }}</span>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
    @php
        $stats = [
            ['icon' => 'bi-currency-exchange', 'label' => 'Total Revenue', 'value' => formatPrice($totalRevenue ?? 0, 0), 'tile' => 'tile-gold'],
            ['icon' => 'bi-receipt', 'label' => 'Total Orders', 'value' => number_format($totalOrders ?? 0), 'tile' => 'tile-indigo'],
            ['icon' => 'bi-people-fill', 'label' => 'Customers', 'value' => number_format($totalUsers ?? 0), 'tile' => 'tile-grass'],
            ['icon' => 'bi-box-seam-fill', 'label' => 'Products', 'value' => number_format($totalProducts ?? 0), 'tile' => 'tile-indigo'],
        ];
    @endphp
    @foreach($stats as $stat)
    <div class="stat-card os-card os-card-hover p-5">
        <span class="stat-tile {{ $stat['tile'] }}"><i class="bi {{ $stat['icon'] }}"></i></span>
        <p class="stat-value mt-4">{{ $stat['value'] }}</p>
        <p class="stat-label mt-1">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>

<!-- Plan lifecycle -->
<div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @php
        $lifecycle = [
            ['icon' => 'bi-play-circle-fill', 'label' => 'Active Plans', 'value' => number_format($activePlans ?? 0), 'tile' => 'tile-indigo'],
            ['icon' => 'bi-check-circle-fill', 'label' => 'Completed Plans', 'value' => number_format($completedPlans ?? 0), 'tile' => 'tile-grass'],
            ['icon' => 'bi-x-circle-fill', 'label' => 'Cancelled Plans', 'value' => number_format($cancelledPlans ?? 0), 'tile' => 'tile-ember'],
            ['icon' => 'bi-exclamation-triangle-fill', 'label' => 'Overdue Installments', 'value' => number_format($overduePayments ?? 0), 'tile' => 'tile-gold', 'sub' => '₦'.number_format($dueThisMonth ?? 0, 0).' due this month'],
        ];
    @endphp
    @foreach($lifecycle as $card)
    <div class="stat-card os-card os-card-hover p-5">
        <span class="stat-tile {{ $card['tile'] }}"><i class="bi {{ $card['icon'] }}"></i></span>
        <p class="stat-value mt-4">{{ $card['value'] }}</p>
        <p class="stat-label mt-1">{{ $card['label'] }}</p>
        @if(!empty($card['sub']))
        <p class="mt-1 text-[11px] font-medium text-slate/80">{{ $card['sub'] }}</p>
        @endif
    </div>
    @endforeach
</div>

<!-- Pending requests -->
<div class="mt-6 grid gap-4 md:grid-cols-3">
    @php
        $requests = [
            ['route' => 'admin.requests.index', 'icon' => 'bi-arrow-repeat', 'label' => 'Pending Plan Changes', 'count' => $pendingPlanChanges ?? 0, 'tile' => 'tile-indigo'],
            ['route' => 'admin.requests.index', 'icon' => 'bi-plus-circle', 'label' => 'Product Requests', 'count' => $pendingProductRequests ?? 0, 'tile' => 'tile-gold'],
            ['route' => 'admin.requests.index', 'icon' => 'bi-arrow-left-right', 'label' => 'Exchange Requests', 'count' => $pendingExchanges ?? 0, 'tile' => 'tile-indigo'],
        ];
    @endphp
    @foreach($requests as $req)
    <a href="{{ route($req['route']) }}" class="os-card os-card-hover flex items-center gap-4 p-5">
        <span class="stat-tile shrink-0 {{ $req['tile'] }}"><i class="bi {{ $req['icon'] }}"></i></span>
        <div class="min-w-0 flex-1">
            <p class="truncate text-xs font-semibold text-slate">{{ $req['label'] }}</p>
            <p class="stat-value mt-0.5 text-2xl">{{ $req['count'] }}</p>
        </div>
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-ink/10 text-slate transition-colors group-hover:border-mango group-hover:text-mango-deep"><i class="bi bi-chevron-right"></i></span>
    </a>
    @endforeach
</div>

<!-- Recent activity -->
<div class="mt-6 grid gap-6 lg:grid-cols-2">

    <!-- Recent orders -->
    <div class="os-card overflow-hidden">
        <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
            <h3 class="section-title"><i class="bi bi-clock-history"></i> Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-brand transition-colors hover:text-mango-deep">View all</a>
        </div>
        <div class="divide-y divide-ink/5">
            @forelse($recentOrders ?? [] as $o)
            <div class="flex items-center gap-3.5 px-5 py-3.5">
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
            <h3 class="section-title"><i class="bi bi-person-plus-fill"></i> New Customers</h3>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-brand transition-colors hover:text-mango-deep">View all</a>
        </div>
        <div class="divide-y divide-ink/5">
            @forelse($recentUsers ?? [] as $u)
            <div class="flex items-center gap-3.5 px-5 py-3.5">
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
        <h3 class="section-title"><i class="bi bi-credit-card-2-front"></i> Recent Transactions</h3>
        <a href="{{ route('admin.transactions.index') }}" class="text-xs font-semibold text-brand transition-colors hover:text-mango-deep">View all</a>
    </div>
    <div class="divide-y divide-ink/5">
        @forelse($recentTransactions ?? [] as $t)
        <div class="flex flex-wrap items-center gap-3.5 px-5 py-3.5">
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
    <h3 class="section-title"><i class="bi bi-lightning-fill"></i> Quick Actions</h3>
    <div class="mt-4 flex flex-wrap gap-2.5">
        @php
            $actions = [
                ['route' => 'admin.products.create', 'icon' => 'bi-plus-lg', 'label' => 'Add Product'],
                ['route' => 'admin.category.create', 'icon' => 'bi-tag', 'label' => 'New Category'],
                ['route' => 'admin.brands.create', 'icon' => 'bi-building', 'label' => 'Add Brand'],
                ['route' => 'admin.suppliers.create', 'icon' => 'bi-truck', 'label' => 'New Supplier'],
                ['route' => 'admin.campaigns.create', 'icon' => 'bi-megaphone', 'label' => 'Create Campaign'],
                ['route' => 'admin.reporting.index', 'icon' => 'bi-graph-up-arrow', 'label' => 'Reporting'],
                ['route' => 'admin.settings', 'icon' => 'bi-gear', 'label' => 'Settings'],
                ['route' => 'admin.orders.export', 'icon' => 'bi-download', 'label' => 'Export Orders'],
            ];
        @endphp
        @foreach($actions as $action)
        <a href="{{ route($action['route']) }}" class="inline-flex items-center gap-2 rounded-lg border border-ink/15 bg-white px-3.5 py-2 text-xs font-semibold text-ink transition-all hover:-translate-y-0.5 hover:border-mango hover:bg-mango/5 hover:shadow-soft">
            <i class="bi {{ $action['icon'] }}"></i> {{ $action['label'] }}
        </a>
        @endforeach
    </div>
</div>

@endsection
