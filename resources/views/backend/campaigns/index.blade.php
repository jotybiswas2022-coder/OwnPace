@extends('backend.layouts.console')
@section('title', 'Campaigns — '.storeName().' Admin')
@section('page_title', 'Campaigns')
@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Campaigns']]])
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
    <div>
        <h2 class="font-display text-lg font-bold text-ink">Broadcast campaigns</h2>
        <p class="text-sm text-slate">Compose, segment, schedule and measure every message.</p>
    </div>
    <a href="{{ route('admin.campaigns.create') }}" class="os-btn os-btn-mango"><i class="bi bi-plus-lg"></i> New Campaign</a>
</div>

<!-- Platform stats -->
<div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
    @php
        $stats = [
            ['icon' => 'bi-megaphone-fill', 'label' => 'Campaigns', 'value' => number_format($totals['campaigns'] ?? 0), 'tone' => 'bg-brand/10 text-brand'],
            ['icon' => 'bi-people-fill', 'label' => 'Total Recipients', 'value' => number_format($totals['recipients'] ?? 0), 'tone' => 'bg-mango/15 text-mango-deep'],
            ['icon' => 'bi-eye-fill', 'label' => 'Avg Open Rate', 'value' => ($totals['open_rate'] ?? 0).'%', 'tone' => 'bg-grass/10 text-grass'],
            ['icon' => 'bi-cursor-fill', 'label' => 'Avg Click Rate', 'value' => ($totals['click_rate'] ?? 0).'%', 'tone' => 'bg-indigo/10 text-indigo'],
        ];
    @endphp
    @foreach($stats as $stat)
    <div class="os-card p-5">
        <div class="flex items-center justify-between">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl text-lg {{ $stat['tone'] }}"><i class="bi {{ $stat['icon'] }}"></i></span>
        </div>
        <p class="mt-4 font-mono text-2xl font-semibold tracking-tight text-ink">{{ $stat['value'] }}</p>
        <p class="mt-1 text-xs font-medium text-slate">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>

<!-- Campaigns table -->
<div class="os-card mt-6 overflow-hidden">
    <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
        <h3 class="font-display text-sm font-bold text-ink"><i class="bi bi-list-ul me-2 text-mango-deep"></i>All campaigns</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="os-table min-w-[820px]">
            <thead>
                <tr>
                    <th>Campaign</th>
                    <th>Channel</th>
                    <th>Audience</th>
                    <th class="text-right">Recipients</th>
                    <th class="text-right">Delivered</th>
                    <th class="text-right">Opened</th>
                    <th class="text-right">Clicked</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $c)
                <tr>
                    <td data-label="Campaign">
                        <a href="{{ route('admin.campaigns.show', $c) }}" class="font-semibold text-ink hover:text-mango-deep">{{ $c->name }}</a>
                        @if($c->subject)
                        <div class="text-xs text-slate">{{ \Illuminate\Support\Str::limit($c->subject, 42) }}</div>
                        @endif
                    </td>
                    <td data-label="Channel">
                        <span class="os-chip os-chip-brand">
                            <i class="bi {{ $c->channel === 'sms' ? 'bi-chat-dots-fill' : 'bi-envelope-fill' }}"></i>
                            {{ $c->channel === 'both' ? 'Email + SMS' : ucfirst($c->channel) }}
                        </span>
                    </td>
                    <td data-label="Audience" class="text-xs text-slate">{{ $c->audience_label }}</td>
                    <td data-label="Recipients" class="money text-right">{{ number_format($c->logs_count ?? 0) }}</td>
                    <td data-label="Delivered" class="money text-right">{{ number_format($metrics[$c->id]['delivered'] ?? 0) }}</td>
                    <td data-label="Opened" class="money text-right">
                        {{ number_format($metrics[$c->id]['opened'] ?? 0) }}
                        <span class="text-[11px] text-slate">({{ $metrics[$c->id]['open_rate'] ?? 0 }}%)</span>
                    </td>
                    <td data-label="Clicked" class="money text-right">
                        {{ number_format($metrics[$c->id]['clicked'] ?? 0) }}
                        <span class="text-[11px] text-slate">({{ $metrics[$c->id]['click_rate'] ?? 0 }}%)</span>
                    </td>
                    <td data-label="Status">
                        @php
                            $chip = match ($c->status) {
                                'sent' => 'os-chip-grass',
                                'sending' => 'os-chip-mango',
                                'scheduled' => 'os-chip-brand',
                                'partial' => 'os-chip-mango',
                                'failed' => 'os-chip-ember',
                                default => 'os-chip-slate',
                            };
                        @endphp
                        <span class="os-chip {{ $chip }}">{{ ucfirst($c->status) }}</span>
                    </td>
                    <td data-label="Actions" class="text-right">
                        <div class="inline-flex gap-2">
                            <a href="{{ route('admin.campaigns.show', $c) }}" class="os-btn os-btn-ghost os-btn-sm" title="Metrics"><i class="bi bi-graph-up"></i></a>
                            @if(in_array($c->status, ['draft', 'scheduled'], true))
                            <form action="{{ route('admin.campaigns.send', $c) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="os-btn os-btn-mango os-btn-sm" title="Send now" onclick="return confirm('Send this campaign to all recipients now?')"><i class="bi bi-send-fill"></i></button>
                            </form>
                            @endif
                            <a href="{{ route('admin.campaigns.delete', $c) }}" class="os-btn os-btn-danger os-btn-sm" title="Delete" onclick="return confirm('Delete this campaign and its logs? This cannot be undone.')"><i class="bi bi-trash-fill"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="py-14 text-center text-slate">
                        <i class="bi bi-megaphone block text-4xl text-ink/10"></i>
                        <p class="mt-3 text-sm">No campaigns yet — <a href="{{ route('admin.campaigns.create') }}" class="font-semibold text-mango-deep hover:underline">compose your first broadcast</a>.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($campaigns->hasPages())
<div class="mt-5">{{ $campaigns->links() }}</div>
@endif
@endsection
