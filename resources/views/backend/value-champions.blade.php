@extends('backend.layouts.console')
@section('title', 'Value Champions — '.storeName().' Admin')
@section('page_title', 'Value Champion Leaderboard')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Marketing'], ['label' => 'Value Champions']]])
@endsection

@php
$champions = [
    ['rank' => 1, 'name' => 'Amara Okafor', 'role' => 'Customer Success Lead', 'points' => 9850, 'badge' => '🏆', 'values' => ['Customer Trust', 'Flexibility', 'Speed'], 'avatar' => 'AO', 'color' => '#eab308', 'streak' => '12 weeks', 'resolved' => 347, 'satisfaction' => '99%'],
    ['rank' => 2, 'name' => 'Chidi Okonkwo', 'role' => 'Sales Associate', 'points' => 8720, 'badge' => '🥈', 'values' => ['Innovation', 'Teamwork', 'Speed'], 'avatar' => 'CO', 'color' => '#a1a1aa', 'streak' => '8 weeks', 'resolved' => 289, 'satisfaction' => '97%'],
    ['rank' => 3, 'name' => 'Zainab Bello', 'role' => 'Support Specialist', 'points' => 7640, 'badge' => '🥉', 'values' => ['Reliability', 'Customer Trust', 'Teamwork'], 'avatar' => 'ZB', 'color' => '#cd7f32', 'streak' => '6 weeks', 'resolved' => 312, 'satisfaction' => '98%'],
    ['rank' => 4, 'name' => 'Emeka Nwosu', 'role' => 'Operations Manager', 'points' => 6510, 'badge' => '💎', 'values' => ['Reliability', 'Flexibility'], 'avatar' => 'EN', 'color' => '#60a5fa', 'streak' => '4 weeks', 'resolved' => 198, 'satisfaction' => '96%'],
    ['rank' => 5, 'name' => 'Folake Adeyemi', 'role' => 'Product Specialist', 'points' => 5430, 'badge' => '💎', 'values' => ['Innovation', 'Customer Trust'], 'avatar' => 'FA', 'color' => '#a78bfa', 'streak' => '3 weeks', 'resolved' => 176, 'satisfaction' => '95%'],
    ['rank' => 6, 'name' => 'Tunde Balogun', 'role' => 'Logistics Coordinator', 'points' => 4380, 'badge' => '💎', 'values' => ['Speed', 'Reliability'], 'avatar' => 'TB', 'color' => '#34d399', 'streak' => '2 weeks', 'resolved' => 154, 'satisfaction' => '94%'],
    ['rank' => 7, 'name' => 'Chioma Eze', 'role' => 'Customer Experience', 'points' => 3290, 'badge' => '💎', 'values' => ['Teamwork', 'Flexibility'], 'avatar' => 'CE', 'color' => '#f472b6', 'streak' => '1 week', 'resolved' => 132, 'satisfaction' => '93%'],
];

$coreValues = [
    ['icon' => 'bi-shield-fill-check', 'name' => 'Customer Trust', 'color' => '#eab308', 'desc' => 'Always transparent about terms & fees'],
    ['icon' => 'bi-arrow-repeat', 'name' => 'Flexibility', 'color' => '#60a5fa', 'desc' => 'Find the right plan, not just any plan'],
    ['icon' => 'bi-lightning-fill', 'name' => 'Speed', 'color' => '#34d399', 'desc' => 'Process requests with instant approval mindset'],
    ['icon' => 'bi-patch-check-fill', 'name' => 'Reliability', 'color' => '#a78bfa', 'desc' => 'Follow through on delivery promises'],
    ['icon' => 'bi-gear-fill', 'name' => 'Innovation', 'color' => '#f472b6', 'desc' => 'Suggest improvements, take initiative'],
    ['icon' => 'bi-people-fill', 'name' => 'Teamwork', 'color' => '#fb923c', 'desc' => 'Share knowledge, help others grow'],
];
@endphp

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="font-display text-xl font-bold text-ink"><i class="bi bi-trophy-fill text-mango-deep"></i> Top Value Champions</h2>
            <p class="mt-1 text-sm text-slate">Recognizing team members who exemplify {{ storeName() }}'s core values every day</p>
        </div>
        <span class="os-chip os-chip-grass"><i class="bi bi-calendar-week"></i> This Quarter</span>
    </div>

    {{-- Podium --}}
    <div class="os-card p-6">
        <div class="flex items-end justify-center gap-3 sm:gap-6">
            @php $top3 = array_slice($champions, 0, 3); @endphp
            @foreach($top3 as $i => $c)
            <div class="flex w-1/3 flex-col items-center gap-2" style="order:{{ $i == 0 ? 2 : ($i == 1 ? 1 : 3) }}" x-reveal="{{ $i * 80 }}">
                <div class="flex h-12 w-12 items-center justify-center rounded-full text-base font-bold sm:h-16 sm:w-16 sm:text-xl" style="background:{{ $c['color'] }}20; color:{{ $c['color'] }}; border:2px solid {{ $c['color'] }};">{{ $c['avatar'] }}</div>
                <div class="flex h-9 w-9 items-center justify-center rounded-full text-lg shadow-sm sm:h-11 sm:w-11 sm:text-xl
                    {{ $i == 0 ? 'bg-mango' : ($i == 1 ? 'bg-slate/40' : 'bg-ember/20') }}">{{ $c['badge'] }}</div>
                <p class="text-center text-xs font-bold text-ink sm:text-sm">{{ explode(' ', $c['name'])[0] }}</p>
                <p class="text-center font-mono text-[11px] font-semibold text-mango-ink sm:text-xs">{{ number_format($c['points']) }} pts</p>
                <div class="mt-1 w-14 rounded-t-lg sm:w-20 {{ $i == 0 ? 'h-20 bg-mango' : ($i == 1 ? 'h-14 bg-slate/50' : 'h-10 bg-ember/30') }}"></div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Full leaderboard --}}
    <div class="os-card overflow-hidden">
        <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
            <h3 class="font-display text-sm font-bold text-ink"><i class="bi bi-list-ol text-brand"></i> Full Leaderboard</h3>
            <span class="text-xs text-slate">{{ count($champions) }} champions</span>
        </div>
        <div class="space-y-2 p-5">
            @foreach($champions as $c)
            <div class="flex flex-wrap items-center gap-3 rounded-xl border bg-white p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-ink/20 hover:shadow-soft {{ $c['rank'] == 1 ? 'border-mango/30 bg-gradient-to-r from-mango/5 to-transparent' : 'border-ink/10' }}">
                <span class="w-8 text-center font-display text-lg font-black {{ $c['rank'] == 1 ? 'text-mango-deep' : ($c['rank'] == 2 ? 'text-slate' : ($c['rank'] == 3 ? 'text-ember-deep' : 'text-slate/50')) }}">#{{ $c['rank'] }}</span>
                <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl text-sm font-extrabold" style="background:{{ $c['color'] }}15; color:{{ $c['color'] }};">{{ $c['avatar'] }}</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold text-ink">{{ $c['name'] }}</p>
                    <p class="text-xs text-slate">{{ $c['role'] }}</p>
                    <div class="mt-1.5 flex flex-wrap gap-1.5">
                        @foreach($c['values'] as $v)
                        <span class="inline-flex items-center gap-1 rounded-md bg-mango/10 px-2 py-0.5 text-[10px] font-semibold text-mango-ink"><i class="bi bi-star-fill text-[7px]"></i> {{ $v }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="flex flex-shrink-0 gap-4 text-center">
                    <div><p class="font-display text-sm font-bold text-ink">{{ $c['resolved'] }}</p><p class="text-[10px] uppercase tracking-wide text-slate">Resolved</p></div>
                    <div><p class="font-display text-sm font-bold text-ink">{{ $c['satisfaction'] }}</p><p class="text-[10px] uppercase tracking-wide text-slate">Satis.</p></div>
                    <div><p class="font-display text-sm font-bold text-ink">{{ $c['streak'] }}</p><p class="text-[10px] uppercase tracking-wide text-slate">Streak</p></div>
                </div>
                <span class="text-lg" aria-hidden="true">{{ $c['badge'] }}</span>
                <p class="w-16 text-right font-mono text-sm font-extrabold text-mango-ink">{{ number_format($c['points']) }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Core values --}}
    <div class="os-card overflow-hidden">
        <div class="border-b border-ink/10 px-5 py-4">
            <h3 class="font-display text-sm font-bold text-ink"><i class="bi bi-heart-fill text-ember"></i> {{ storeName() }} Core Values</h3>
        </div>
        <div class="grid gap-3 p-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($coreValues as $v)
            <div class="rounded-xl border border-ink/10 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-ink/20 hover:shadow-soft">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg text-base" style="background:{{ $v['color'] }}15; color:{{ $v['color'] }};"><i class="bi {{ $v['icon'] }}"></i></span>
                <h4 class="mt-3 text-sm font-bold text-ink">{{ $v['name'] }}</h4>
                <p class="mt-1 text-xs leading-relaxed text-slate">{{ $v['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- About --}}
    <div class="os-card p-5">
        <h3 class="font-display text-sm font-bold text-ink"><i class="bi bi-info-circle-fill text-mango-deep"></i> About This Leaderboard</h3>
        <p class="mt-2 text-sm leading-relaxed text-slate">
            The Value Champion leaderboard recognizes team members who demonstrate {{ storeName() }}'s core values.
            Points are earned through positive customer feedback, successful issue resolution, team collaboration,
            and innovative contributions. Top champions are celebrated quarterly with rewards and recognition.
        </p>
    </div>
</div>
@endsection
