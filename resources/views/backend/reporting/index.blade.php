@extends('backend.layouts.console')
@section('title', 'Reporting — '.storeName().' Admin')
@section('page_title', 'Reporting')
@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Reporting']]])
@endsection

@php
    $periods = [7 => '7 days', 30 => '30 days', 90 => '90 days', 365 => '12 months'];
    $pChart = fn ($v) => (float) $v;
@endphp

@section('content')

<!-- Header -->
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="font-display text-lg font-bold text-ink">Store performance</h2>
        <p class="text-sm text-slate">Sales, installment health and customer behaviour — export any report to CSV.</p>
    </div>
    <div class="flex items-center gap-1 rounded-xl border border-ink/10 bg-white p-1">
        @foreach($periods as $days => $label)
        <a href="{{ route('admin.reporting.index', ['period' => $days]) }}"
           class="rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors {{ $days === (int) request('period', 30) ? 'bg-brand text-white' : 'text-slate hover:text-ink' }}">{{ $label }}</a>
        @endforeach
    </div>
</div>

<!-- ===================== SALES OVER TIME ===================== -->
<div class="os-card p-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h3 class="flex items-center gap-2 font-display text-base font-bold text-ink"><i class="bi bi-currency-exchange text-mango-deep"></i> Sales over time</h3>
        <a href="{{ route('admin.reporting.export', ['report' => 'sales', 'period' => $days]) }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-download"></i> Export CSV</a>
    </div>

    <div class="mt-5 grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-xl border border-ink/10 p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate">Revenue</p>
            <p class="money mt-1 text-2xl font-semibold text-ink">{{ formatPrice($sales['revenueTotal'], 0) }}</p>
        </div>
        <div class="rounded-xl border border-ink/10 p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate">Orders</p>
            <p class="money mt-1 text-2xl font-semibold text-ink">{{ number_format($sales['orderTotal']) }}</p>
        </div>
        <div class="rounded-xl border border-ink/10 p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate">Avg order value</p>
            <p class="money mt-1 text-2xl font-semibold text-ink">{{ formatPrice($sales['aov'], 0) }}</p>
        </div>
        <div class="rounded-xl border border-ink/10 p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate">vs previous period</p>
            <p class="money mt-1 flex items-center gap-1.5 text-2xl font-semibold {{ $sales['change'] >= 0 ? 'text-grass' : 'text-ember' }}">
                <i class="bi {{ $sales['change'] >= 0 ? 'bi-arrow-up-right' : 'bi-arrow-down-right' }}"></i>{{ abs($sales['change']) }}%
            </p>
        </div>
    </div>

    <div class="mt-5 h-72">
        <canvas id="salesChart" aria-label="Revenue and orders over time"></canvas>
    </div>
</div>

<!-- ===================== INSTALLMENT PERFORMANCE ===================== -->
@php
    $inst = $installments['breakdown'];
    $instTotal = $installments['total'];
@endphp
<div class="os-card mt-6 p-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h3 class="flex items-center gap-2 font-display text-base font-bold text-ink"><i class="bi bi-calendar2-check text-mango-deep"></i> Installment performance</h3>
        <a href="{{ route('admin.reporting.export', ['report' => 'installments']) }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-download"></i> Export CSV</a>
    </div>

    <div class="mt-5 grid gap-6 lg:grid-cols-2">
        <div class="flex items-center gap-6">
            <div class="relative h-52 w-52 shrink-0">
                <canvas id="installmentChart" aria-label="Installment performance split"></canvas>
            </div>
            <div class="space-y-3 text-sm">
                @php
                    $legend = [
                        ['label' => 'On-time', 'count' => $inst['on_time']['count'], 'amount' => $inst['on_time']['amount'], 'dot' => '#2f9e44'],
                        ['label' => 'Late (paid after due)', 'count' => $inst['late']['count'], 'amount' => $inst['late']['amount'], 'dot' => '#f5a623'],
                        ['label' => 'Overdue (< 30d)', 'count' => $inst['overdue']['count'], 'amount' => $inst['overdue']['amount'], 'dot' => '#2e2a6b'],
                        ['label' => 'Defaulted (30d+)', 'count' => $inst['defaulted']['count'], 'amount' => $inst['defaulted']['amount'], 'dot' => '#e0483e'],
                    ];
                @endphp
                @foreach($legend as $item)
                <div class="flex items-center gap-3">
                    <span class="h-2.5 w-2.5 rounded-full" style="background:{{ $item['dot'] }}"></span>
                    <span class="flex-1 text-slate">{{ $item['label'] }}</span>
                    <span class="money text-ink">{{ number_format($item['count']) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="os-table min-w-[560px]">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th class="text-right">Due</th>
                        <th class="text-right">On-time</th>
                        <th class="text-right">Late</th>
                        <th class="text-right">Overdue</th>
                        <th class="text-right">Defaulted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($installments['months'] as $m)
                    <tr>
                        <td data-label="Month" class="text-xs font-semibold text-ink">{{ $m['label'] }}</td>
                        <td data-label="Due" class="money text-right">{{ $m['due'] }}</td>
                        <td data-label="On-time" class="money text-right text-grass">{{ $m['on_time'] }}</td>
                        <td data-label="Late" class="money text-right text-mango-deep">{{ $m['late'] }}</td>
                        <td data-label="Overdue" class="money text-right text-brand">{{ $m['overdue'] }}</td>
                        <td data-label="Defaulted" class="money text-right text-ember">{{ $m['defaulted'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===================== CUSTOMER BEHAVIOR ===================== -->
<div class="os-card mt-6 p-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h3 class="flex items-center gap-2 font-display text-base font-bold text-ink"><i class="bi bi-people-fill text-mango-deep"></i> Customer behavior</h3>
        <a href="{{ route('admin.reporting.export', ['report' => 'customers']) }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-download"></i> Export CSV</a>
    </div>

    <div class="mt-5 grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-xl border border-ink/10 p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate">Total customers</p>
            <p class="money mt-1 text-2xl font-semibold text-ink">{{ number_format($behavior['totalCustomers']) }}</p>
        </div>
        <div class="rounded-xl border border-ink/10 p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate">Buyers</p>
            <p class="money mt-1 text-2xl font-semibold text-ink">{{ number_format($behavior['buyers']) }}</p>
        </div>
        <div class="rounded-xl border border-ink/10 p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate">Repeat purchase rate</p>
            <p class="money mt-1 text-2xl font-semibold text-ink">{{ $behavior['repeatRate'] }}%</p>
            <p class="text-[11px] text-slate">{{ number_format($behavior['repeatBuyers']) }} customers with 2+ orders</p>
        </div>
        <div class="rounded-xl border border-ink/10 p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate">Average order value</p>
            <p class="money mt-1 text-2xl font-semibold text-ink">{{ formatPrice($behavior['aov'], 0) }}</p>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <h4 class="font-display text-sm font-bold text-ink">Most-requested products <span class="text-xs font-normal text-slate">(by units ordered)</span></h4>
            <div class="mt-3 h-64">
                <canvas id="productsChart" aria-label="Most requested products"></canvas>
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <h4 class="font-display text-sm font-bold text-ink">Top customers</h4>
                <div class="mt-3 divide-y divide-ink/5 rounded-xl border border-ink/10">
                    @forelse($behavior['topCustomers'] as $c)
                    <div class="flex items-center gap-3 px-4 py-2.5">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand/10 font-display text-xs font-bold text-brand">{{ strtoupper(substr($c['name'], 0, 1)) }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-ink">{{ $c['name'] }}</p>
                            <p class="text-[11px] text-slate">{{ $c['order_count'] }} orders</p>
                        </div>
                        <span class="money text-sm font-semibold text-ink">{{ formatPrice($c['spend'], 0) }}</span>
                    </div>
                    @empty
                    <p class="px-4 py-8 text-center text-sm text-slate">No customers yet</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h4 class="font-display text-sm font-bold text-ink">Most requested <span class="text-xs font-normal text-slate">(not in store)</span></h4>
                <div class="mt-3 divide-y divide-ink/5 rounded-xl border border-ink/10">
                    @forelse($behavior['topRequests'] as $r)
                    <div class="flex items-center justify-between gap-3 px-4 py-2.5">
                        <span class="truncate text-sm text-ink">{{ $r['label'] }}</span>
                        <span class="money shrink-0 text-sm font-semibold text-ink">{{ $r['requests'] }}</span>
                    </div>
                    @empty
                    <p class="px-4 py-8 text-center text-sm text-slate">No product requests yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
<script>
(function () {
    const palette = {
        mango: '#f5a623',
        mangoDeep: '#d98c0f',
        indigo: '#2e2a6b',
        indigoSoft: '#4a4599',
        grass: '#2f9e44',
        ember: '#e0483e',
        grid: 'rgba(26,27,35,0.06)',
        tick: 'rgba(26,27,35,0.55)',
    };

    Chart.defaults.color = palette.tick;
    Chart.defaults.font.family = "'Inter', ui-sans-serif, sans-serif";
    Chart.defaults.font.size = 11;

    const mono = "'IBM Plex Mono', ui-monospace, monospace";
    const display = "'Space Grotesk', ui-sans-serif, sans-serif";

    const tooltipStyle = {
        backgroundColor: palette.indigo,
        titleFont: { family: display },
        bodyFont: { family: mono },
        padding: 10,
        cornerRadius: 8,
    };

    // ---- Sales: mango revenue line + indigo order bars ----
    const sales = document.getElementById('salesChart');
    if (sales) {
        new Chart(sales, {
            data: {
                labels: @json($sales['labels']),
                datasets: [
                    {
                        type: 'bar',
                        label: 'Orders',
                        data: @json($sales['orders']),
                        backgroundColor: 'rgba(46,42,107,0.14)',
                        hoverBackgroundColor: 'rgba(46,42,107,0.28)',
                        borderRadius: 4,
                        order: 2,
                    },
                    {
                        type: 'line',
                        label: 'Revenue',
                        data: @json($sales['revenue']),
                        borderColor: palette.mango,
                        backgroundColor: 'rgba(245,166,35,0.12)',
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: palette.mangoDeep,
                        pointRadius: 3,
                        borderWidth: 2.5,
                        order: 1,
                        yAxisID: 'y',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { labels: { usePointStyle: true, boxWidth: 8, padding: 16 } },
                    tooltip: tooltipStyle,
                },
                scales: {
                    x: { grid: { color: palette.grid }, border: { color: palette.grid }, ticks: { font: { family: mono } } },
                    y: {
                        beginAtZero: true,
                        grid: { color: palette.grid },
                        border: { display: false },
                        ticks: { font: { family: mono }, callback: (v) => '₦' + (v >= 1000 ? (v / 1000) + 'k' : v) },
                    },
                },
            },
        });
    }

    // ---- Installment donut ----
    const donut = document.getElementById('installmentChart');
    if (donut) {
        new Chart(donut, {
            type: 'doughnut',
            data: {
                labels: ['On-time', 'Late', 'Overdue', 'Defaulted'],
                datasets: [{
                    data: [
                        {{ $inst['on_time']['count'] }},
                        {{ $inst['late']['count'] }},
                        {{ $inst['overdue']['count'] }},
                        {{ $inst['defaulted']['count'] }},
                    ],
                    backgroundColor: [palette.grass, palette.mango, palette.indigo, palette.ember],
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverOffset: 8,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        ...tooltipStyle,
                        callbacks: {
                            label: (ctx) => {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? Math.round((ctx.parsed / total) * 100) : 0;
                                return ` ${ctx.label}: ${ctx.parsed.toLocaleString()} (${pct}%)`;
                            },
                        },
                    },
                },
            },
        });
    }

    // ---- Top products horizontal bars ----
    const products = document.getElementById('productsChart');
    if (products) {
        new Chart(products, {
            type: 'bar',
            data: {
                labels: @json($behavior['topProducts']->map(fn ($p) => \Illuminate\Support\Str::limit($p->label, 26))->values()),
                datasets: [{
                    label: 'Units ordered',
                    data: @json($behavior['topProducts']->pluck('units')->map(fn ($u) => (int) $u)->values()),
                    backgroundColor: 'rgba(245,166,35,0.55)',
                    hoverBackgroundColor: palette.mangoDeep,
                    borderRadius: 5,
                    borderSkipped: false,
                }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: tooltipStyle,
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: palette.grid },
                        border: { display: false },
                        ticks: { font: { family: mono }, precision: 0 },
                    },
                    y: {
                        grid: { display: false },
                        border: { color: palette.grid },
                        ticks: { font: { family: display, size: 11 } },
                    },
                },
            },
        });
    }
})();
</script>
@endpush
