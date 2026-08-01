@extends('backend.app')
@section('title', 'Value Champions — Admin')
@section('page_title', '🏆 Value Champion Leaderboard')

@section('content')

@php
$champions = [
    [
        'rank' => 1,
        'name' => 'Amara Okafor',
        'role' => 'Customer Success Lead',
        'points' => 9850,
        'badge' => '🏆',
        'values' => ['Customer Trust', 'Flexibility', 'Speed'],
        'avatar' => 'AO',
        'color' => '#eab308',
        'streak' => '12 weeks',
        'resolved' => 347,
        'satisfaction' => '99%',
    ],
    [
        'rank' => 2,
        'name' => 'Chidi Okonkwo',
        'role' => 'Sales Associate',
        'points' => 8720,
        'badge' => '🥈',
        'values' => ['Innovation', 'Teamwork', 'Speed'],
        'avatar' => 'CO',
        'color' => '#a1a1aa',
        'streak' => '8 weeks',
        'resolved' => 289,
        'satisfaction' => '97%',
    ],
    [
        'rank' => 3,
        'name' => 'Zainab Bello',
        'role' => 'Support Specialist',
        'points' => 7640,
        'badge' => '🥉',
        'values' => ['Reliability', 'Customer Trust', 'Teamwork'],
        'avatar' => 'ZB',
        'color' => '#cd7f32',
        'streak' => '6 weeks',
        'resolved' => 312,
        'satisfaction' => '98%',
    ],
    [
        'rank' => 4,
        'name' => 'Emeka Nwosu',
        'role' => 'Operations Manager',
        'points' => 6510,
        'badge' => '💎',
        'values' => ['Reliability', 'Flexibility'],
        'avatar' => 'EN',
        'color' => '#60a5fa',
        'streak' => '4 weeks',
        'resolved' => 198,
        'satisfaction' => '96%',
    ],
    [
        'rank' => 5,
        'name' => 'Folake Adeyemi',
        'role' => 'Product Specialist',
        'points' => 5430,
        'badge' => '💎',
        'values' => ['Innovation', 'Customer Trust'],
        'avatar' => 'FA',
        'color' => '#a78bfa',
        'streak' => '3 weeks',
        'resolved' => 176,
        'satisfaction' => '95%',
    ],
    [
        'rank' => 6,
        'name' => 'Tunde Balogun',
        'role' => 'Logistics Coordinator',
        'points' => 4380,
        'badge' => '💎',
        'values' => ['Speed', 'Reliability'],
        'avatar' => 'TB',
        'color' => '#34d399',
        'streak' => '2 weeks',
        'resolved' => 154,
        'satisfaction' => '94%',
    ],
    [
        'rank' => 7,
        'name' => 'Chioma Eze',
        'role' => 'Customer Experience',
        'points' => 3290,
        'badge' => '💎',
        'values' => ['Teamwork', 'Flexibility'],
        'avatar' => 'CE',
        'color' => '#f472b6',
        'streak' => '1 week',
        'resolved' => 132,
        'satisfaction' => '93%',
    ],
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

<style>
.fp-leaderboard-header {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 16px; margin-bottom: 28px;
}
.fp-lh-info h4 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 18px; font-weight: 800;
    color: var(--text-primary); margin-bottom: 4px;
}
.fp-lh-info p { font-size: 14px; color: var(--text-dim); margin: 0; }
.fp-lh-actions { display: flex; gap: 8px; }

.fp-podium {
    display: flex; align-items: flex-end; justify-content: center;
    gap: 12px; margin-bottom: 36px; padding: 20px 0;
}
.fp-podium-item {
    display: flex; flex-direction: column; align-items: center;
    gap: 8px; transition: all 0.3s;
}
.fp-podium-item:hover { transform: translateY(-4px); }

.fp-podium-rank {
    width: 60px; height: 60px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; font-weight: 800;
}
.fp-podium-rank.gold { background: linear-gradient(135deg, #eab308, #ca8a04); color: #0A0A0B; box-shadow: 0 4px 24px rgba(234,179,8,0.3); }
.fp-podium-rank.silver { background: linear-gradient(135deg, #d4d4d8, #a1a1aa); color: #0A0A0B; box-shadow: 0 4px 20px rgba(161,161,170,0.2); }
.fp-podium-rank.bronze { background: linear-gradient(135deg, #d97706, #b45309); color: #fff; box-shadow: 0 4px 20px rgba(185,83,9,0.2); }

.fp-podium-avatar {
    width: 56px; height: 56px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 800;
    border: 3px solid var(--card-border); transition: all 0.3s;
}
.fp-podium-item:nth-child(1) .fp-podium-avatar { width: 72px; height: 72px; font-size: 22px; border-color: #eab308; box-shadow: 0 0 0 3px rgba(234,179,8,0.2); }
.fp-podium-item:nth-child(2) .fp-podium-avatar { width: 64px; height: 64px; font-size: 20px; border-color: #a1a1aa; }
.fp-podium-item:nth-child(3) .fp-podium-avatar { width: 60px; height: 60px; font-size: 19px; border-color: #cd7f32; }

.fp-podium-name { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; font-weight: 700; color: var(--text-primary); text-align: center; }
.fp-podium-points { font-size: 11px; color: var(--gold-400); font-weight: 600; text-align: center; }
.fp-podium-bar { width: 80px; border-radius: 8px 8px 0 0; margin-top: 4px; }
.fp-podium-bar.gold { height: 120px; background: linear-gradient(180deg, #eab308, #ca8a04); }
.fp-podium-bar.silver { height: 90px; background: linear-gradient(180deg, #d4d4d8, #a1a1aa); }
.fp-podium-bar.bronze { height: 60px; background: linear-gradient(180deg, #d97706, #b45309); }

.fp-champ-card {
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius); padding: 16px 18px;
    display: flex; align-items: center; gap: 16px;
    transition: all 0.3s ease; position: relative; overflow: hidden;
}
.fp-champ-card:hover { border-color: rgba(234,179,8,0.12); transform: translateX(4px); }
.fp-champ-card:first-child {
    border-color: rgba(234,179,8,0.15);
    background: linear-gradient(135deg, rgba(234,179,8,0.04), var(--card-dark));
}
.fp-champ-rank { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 900; color: var(--text-dim); min-width: 28px; text-align: center; }
.fp-champ-rank.top1 { color: #eab308; }
.fp-champ-rank.top2 { color: #a1a1aa; }
.fp-champ-rank.top3 { color: #cd7f32; }

.fp-champ-avatar { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800; flex-shrink: 0; }
.fp-champ-info { flex: 1; min-width: 0; }
.fp-champ-info h5 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 2px; }
.fp-champ-info .fp-champ-role { font-size: 12px; color: var(--text-dim); margin-bottom: 6px; }
.fp-champ-values { display: flex; gap: 4px; flex-wrap: wrap; }
.fp-champ-value-tag { display: inline-flex; align-items: center; gap: 3px; background: rgba(234,179,8,0.08); color: var(--gold-400); padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 600; }

.fp-champ-stats { display: flex; gap: 16px; flex-shrink: 0; }
.fp-champ-stat { text-align: center; }
.fp-champ-stat strong { display: block; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 800; color: var(--text-primary); }
.fp-champ-stat span { font-size: 10px; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.3px; }
.fp-champ-badge-icon { font-size: 22px; }
.fp-champ-points { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 16px; font-weight: 800; color: var(--gold-400); min-width: 70px; text-align: right; }

.fp-values-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; }
.fp-value-card { background: var(--card-dark); border: 1px solid var(--card-border); border-radius: 10px; padding: 16px; transition: all 0.3s; }
.fp-value-card:hover { border-color: rgba(234,179,8,0.1); transform: translateY(-2px); }
.fp-value-card-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-bottom: 10px; }
.fp-value-card h6 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 3px; }
.fp-value-card p { font-size: 12px; color: var(--text-dim); margin: 0; line-height: 1.5; }

@media (max-width: 768px) {
    .fp-champ-card { flex-wrap: wrap; gap: 10px; }
    .fp-champ-stats { gap: 8px; flex-wrap: wrap; }
    .fp-champ-points { min-width: auto; }
    .fp-podium { gap: 8px; }
    .fp-podium-bar { width: 60px; }
}
@media (max-width: 576px) {
    .fp-champ-stat { width: 100%; text-align: left; display: flex; justify-content: space-between; align-items: center; }
    .fp-champ-stat strong { margin-right: 8px; }
    .fp-podium-item { width: 30%; }
    .fp-podium-rank { width: 44px; height: 44px; font-size: 18px; }
    .fp-podium-avatar { width: 44px; height: 44px; font-size: 14px; }
    .fp-podium-item:nth-child(1) .fp-podium-avatar { width: 52px; height: 52px; font-size: 16px; }
    .fp-podium-item:nth-child(2) .fp-podium-avatar { width: 48px; height: 48px; font-size: 15px; }
    .fp-podium-item:nth-child(3) .fp-podium-avatar { width: 44px; height: 44px; font-size: 14px; }
    .fp-podium-bar { width: 40px; height: 40px !important; }
    .fp-values-grid { grid-template-columns: 1fr 1fr; }
}

.fp-confetti {
    position: fixed; top: -10px; left: 0; width: 100%; height: 10px;
    pointer-events: none; z-index: 9999;
    background: linear-gradient(90deg, transparent 0%, #eab308 10%, #ef4444 20%, #3b82f6 30%, #22c55e 40%, #a855f7 50%, #eab308 60%, #ef4444 70%, #3b82f6 80%, #22c55e 90%, transparent 100%);
    background-size: 200% 100%;
    animation: confettiSweep 3s linear infinite;
    opacity: 0.6;
}
@keyframes confettiSweep {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>

<div class="fp-confetti" aria-hidden="true"></div>

<div class="fp-leaderboard-header">
    <div class="fp-lh-info">
        <h4><i class="bi bi-trophy-fill" style="color:var(--gold-500);"></i> Top Value Champions</h4>
        <p>Recognizing team members who exemplify OwnPace's core values every day</p>
    </div>
    <div class="fp-lh-actions">
        <span class="fp-badge fp-badge-active"><i class="bi bi-calendar-week"></i> This Quarter</span>
    </div>
</div>

<div class="fp-podium">
    @php $top3 = array_slice($champions, 0, 3); @endphp
    @foreach($top3 as $i => $c)
    <div class="fp-podium-item" style="order:{{ $i == 0 ? 2 : ($i == 1 ? 1 : 3) }}">
        <div class="fp-podium-avatar" style="background:{{ $c['color'] }}20; color:{{ $c['color'] }};">{{ $c['avatar'] }}</div>
        <div class="fp-podium-rank {{ $i == 0 ? 'gold' : ($i == 1 ? 'silver' : 'bronze') }}">{{ $c['badge'] }}</div>
        <div class="fp-podium-name">{{ explode(' ', $c['name'])[0] }}</div>
        <div class="fp-podium-points">{{ number_format($c['points']) }} pts</div>
        <div class="fp-podium-bar {{ $i == 0 ? 'gold' : ($i == 1 ? 'silver' : 'bronze') }}"></div>
    </div>
    @endforeach
</div>

<div class="fp-table-wrap">
    <div class="fp-table-header">
        <h5><i class="bi bi-list-ol"></i> Full Leaderboard</h5>
        <span style="font-size:12px;color:var(--text-dim);">{{ count($champions) }} champions</span>
    </div>
    <div style="padding:16px 20px;">
        <div style="display:flex;flex-direction:column;gap:8px;">
            @foreach($champions as $c)
            <div class="fp-champ-card">
                <div class="fp-champ-rank @if($c['rank'] <= 3) top{{ $c['rank'] }} @endif">#{{ $c['rank'] }}</div>
                <div class="fp-champ-avatar" style="background:{{ $c['color'] }}15; color:{{ $c['color'] }};">{{ $c['avatar'] }}</div>
                <div class="fp-champ-info">
                    <h5>{{ $c['name'] }}</h5>
                    <div class="fp-champ-role">{{ $c['role'] }}</div>
                    <div class="fp-champ-values">
                        @foreach($c['values'] as $v)
                            <span class="fp-champ-value-tag"><i class="bi bi-star-fill" style="font-size:7px;"></i> {{ $v }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="fp-champ-stats">
                    <div class="fp-champ-stat"><strong>{{ $c['resolved'] }}</strong><span>Resolved</span></div>
                    <div class="fp-champ-stat"><strong>{{ $c['satisfaction'] }}</strong><span>Satis.</span></div>
                    <div class="fp-champ-stat"><strong>{{ $c['streak'] }}</strong><span>Streak</span></div>
                </div>
                <div class="fp-champ-badge-icon">{{ $c['badge'] }}</div>
                <div class="fp-champ-points">{{ number_format($c['points']) }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div style="margin-top:28px;">
    <div class="fp-table-header" style="border:1px solid var(--card-border);border-radius:var(--radius);">
        <h5><i class="bi bi-heart-fill" style="color:#ef4444;"></i> OwnPace Core Values</h5>
    </div>
    <div style="margin-top:12px;">
        <div class="fp-values-grid">
            @foreach($coreValues as $v)
            <div class="fp-value-card">
                <div class="fp-value-card-icon" style="background:{{ $v['color'] }}15; color:{{ $v['color'] }};">
                    <i class="bi {{ $v['icon'] }}"></i>
                </div>
                <h6>{{ $v['name'] }}</h6>
                <p>{{ $v['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div style="margin-top:28px;padding:16px 20px;background:var(--card-dark);border:1px solid var(--card-border);border-radius:var(--radius);">
    <h5 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:6px;">
        <i class="bi bi-info-circle-fill" style="color:var(--gold-500);"></i> About This Leaderboard
    </h5>
    <p style="font-size:13px;color:var(--text-dim);margin:0;line-height:1.7;">
        The Value Champion leaderboard recognizes team members who demonstrate OwnPace's core values.
        Points are earned through positive customer feedback, successful issue resolution, team collaboration,
        and innovative contributions. Top champions are celebrated quarterly with rewards and recognition.
    </p>
</div>

@endsection
