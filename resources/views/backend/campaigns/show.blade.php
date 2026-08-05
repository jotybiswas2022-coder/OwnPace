@extends('backend.layouts.console')
@section('title', $campaign->name.' — Metrics — '.storeName().' Admin')
@section('page_title', $campaign->name)
@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Campaigns', 'route' => 'admin.campaigns.index'], ['label' => $campaign->name]]])
@endsection

@section('content')
@if(session('success'))
<div class="mb-4 flex items-start gap-2 rounded-xl border border-grass/25 bg-grass/10 p-4 text-sm text-grass">
    <i class="bi bi-check-circle-fill mt-0.5"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 flex items-start gap-2 rounded-xl border border-ember/25 bg-ember/10 p-4 text-sm text-ember">
    <i class="bi bi-exclamation-circle-fill mt-0.5"></i> {{ session('error') }}
</div>
@endif

<!-- Header -->
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div class="flex flex-wrap items-center gap-3">
        <h2 class="font-display text-lg font-bold text-ink">{{ $campaign->name }}</h2>
        @php
            $chip = match ($campaign->status) {
                'sent' => 'os-chip-grass',
                'sending' => 'os-chip-mango',
                'scheduled' => 'os-chip-brand',
                'partial' => 'os-chip-mango',
                'failed' => 'os-chip-ember',
                default => 'os-chip-slate',
            };
        @endphp
        <span class="os-chip {{ $chip }}">{{ ucfirst($campaign->status) }}</span>
        @if($campaign->channel === 'both') <span class="os-chip os-chip-brand"><i class="bi bi-envelope-fill"></i>+<i class="bi bi-chat-dots-fill"></i></span>
        @else <span class="os-chip os-chip-brand"><i class="bi {{ $campaign->channel === 'sms' ? 'bi-chat-dots-fill' : 'bi-envelope-fill' }}"></i> {{ ucfirst($campaign->channel) }}</span>
        @endif
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.campaigns.export', $campaign) }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-download"></i> Export logs</a>
        <a href="{{ route('admin.campaigns.index') }}" class="os-btn os-btn-ghost os-btn-sm">Back</a>
    </div>
</div>

<!-- Metrics -->
<div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
    @php
        $cards = [
            ['icon' => 'bi-people-fill', 'label' => 'Recipients', 'value' => number_format($metrics['recipients']), 'tone' => 'bg-brand/10 text-brand'],
            ['icon' => 'bi-send-check-fill', 'label' => 'Delivered', 'value' => number_format($metrics['delivered']), 'tone' => 'bg-grass/10 text-grass'],
            ['icon' => 'bi-eye-fill', 'label' => 'Opened', 'value' => number_format($metrics['opened']).' · '.$metrics['open_rate'].'%', 'tone' => 'bg-mango/15 text-mango-deep'],
            ['icon' => 'bi-cursor-fill', 'label' => 'Clicked', 'value' => number_format($metrics['clicked']).' · '.$metrics['click_rate'].'%', 'tone' => 'bg-indigo/10 text-indigo'],
            ['icon' => 'bi-x-octagon-fill', 'label' => 'Failed', 'value' => number_format($metrics['failed']), 'tone' => 'bg-ember/10 text-ember'],
        ];
    @endphp
    @foreach($cards as $card)
    <div class="os-card p-5">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl text-base {{ $card['tone'] }}"><i class="bi {{ $card['icon'] }}"></i></span>
        <p class="mt-3 font-mono text-xl font-semibold tracking-tight text-ink">{{ $card['value'] }}</p>
        <p class="mt-0.5 text-xs font-medium text-slate">{{ $card['label'] }}</p>
    </div>
    @endforeach
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-3">
    <!-- Delivery chart -->
    <div class="os-card p-5 lg:col-span-2">
        <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-graph-up text-mango-deep"></i> Delivery &amp; opens — last 14 days</h3>
        <div class="mt-4 h-64">
            <canvas id="campaignChart" aria-label="Campaign delivery over time"></canvas>
        </div>
    </div>

    <!-- Top links -->
    <div class="os-card overflow-hidden">
        <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-link-45deg text-mango-deep"></i> Top clicked links</h3>
        </div>
        <div class="divide-y divide-ink/5">
            @forelse($clicks as $click)
            <div class="flex items-center justify-between gap-3 px-5 py-3">
                <span class="truncate text-xs text-slate">{{ $click['url'] }}</span>
                <span class="money shrink-0 text-sm font-semibold text-ink">{{ $click['clicks'] }}</span>
            </div>
            @empty
            <p class="px-5 py-10 text-center text-sm text-slate"><i class="bi bi-mouse me-1"></i> No link clicks yet</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recipient log -->
<div class="os-card mt-6 overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/10 px-5 py-4">
        <h3 class="font-display text-sm font-bold text-ink"><i class="bi bi-list-check me-2 text-mango-deep"></i>Recipient log</h3>
        <form method="GET" class="flex flex-wrap items-center gap-2">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search customer…" class="os-input os-input-sm w-44">
            <select name="status" class="os-input os-input-sm w-32">
                <option value="">All statuses</option>
                @foreach(['pending', 'sent', 'failed'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-funnel-fill"></i></button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="os-table min-w-[760px]">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Channel</th>
                    <th>Status</th>
                    <th>Sent</th>
                    <th>Opened</th>
                    <th class="text-right">Clicks</th>
                    <th>Error</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td data-label="Customer">
                        <p class="font-semibold text-ink">{{ $log->user?->name ?? 'Customer #'.$log->user_id }}</p>
                        <p class="text-xs text-slate">{{ $log->email ?: $log->phone }}</p>
                    </td>
                    <td data-label="Channel"><span class="text-xs">{{ $log->channel_label }}</span></td>
                    <td data-label="Status">
                        @php
                            $lchip = match ($log->status) {
                                'sent' => 'os-chip-grass',
                                'pending' => 'os-chip-slate',
                                default => 'os-chip-ember',
                            };
                        @endphp
                        <span class="os-chip {{ $lchip }}">{{ ucfirst($log->status) }}</span>
                    </td>
                    <td data-label="Sent" class="text-xs text-slate">{{ $log->sent_at?->format('M j, g:i A') ?? '—' }}</td>
                    <td data-label="Opened" class="text-xs text-slate">{{ $log->opened_at?->format('M j, g:i A') ?? '—' }}</td>
                    <td data-label="Clicks" class="money text-right">{{ $log->click_count }}</td>
                    <td data-label="Error" class="max-w-[220px] truncate text-xs text-ember">{{ $log->error }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-slate">
                        <i class="bi bi-inbox block text-3xl text-ink/10"></i>
                        <p class="mt-2 text-sm">No recipient logs yet — the queued send snapshots each customer.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="border-t border-ink/10 p-4">{{ $logs->links() }}</div>
    @endif
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
        grid: 'rgba(26,27,35,0.06)',
        tick: 'rgba(26,27,35,0.55)',
        mono: "'IBM Plex Mono', ui-monospace, monospace",
    };

    Chart.defaults.color = palette.tick;
    Chart.defaults.font.family = "'Inter', ui-sans-serif, sans-serif";
    Chart.defaults.font.size = 11;

    const el = document.getElementById('campaignChart');
    if (!el) return;

    new Chart(el, {
        type: 'line',
        data: {
            labels: @json($series['labels']),
            datasets: [
                {
                    label: 'Sent',
                    data: @json($series['sent']),
                    borderColor: palette.indigo,
                    backgroundColor: 'rgba(46,42,107,0.08)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    borderWidth: 2,
                },
                {
                    label: 'Opened',
                    data: @json($series['opened']),
                    borderColor: palette.mango,
                    backgroundColor: 'rgba(245,166,35,0.10)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    labels: { usePointStyle: true, boxWidth: 8, padding: 16 },
                },
                tooltip: {
                    backgroundColor: palette.indigo,
                    titleFont: { family: "'Space Grotesk', sans-serif" },
                    bodyFont: { family: palette.mono },
                    padding: 10,
                    cornerRadius: 8,
                },
            },
            scales: {
                x: {
                    grid: { color: palette.grid },
                    border: { color: palette.grid },
                    ticks: { font: { family: palette.mono } },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: palette.grid },
                    border: { display: false },
                    ticks: { font: { family: palette.mono }, precision: 0 },
                },
            },
        },
    });
})();
</script>
@endpush
