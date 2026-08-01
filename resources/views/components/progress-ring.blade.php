@props([
    'percentage' => 0,      // 0-100, how much of the plan is paid off
    'amount' => null,       // tabular-mono amount shown in the center
    'label' => null,        // small caption under the amount
    'size' => 120,          // diameter in px
    'stroke' => 8,          // ring thickness in px
    'color' => 'mango',     // mango | grass (paid-off)
    'animate' => true,      // fill in on view
    'className' => '',
])

@php
    $pct = max(0, min(100, (float) $percentage));
    $radius = ($size - $stroke) / 2;
    $circumference = 2 * M_PI * $radius;
    $offset = $circumference * (1 - $pct / 100);
    $done = $pct >= 100;
    $uid = 'pr-' . md5(uniqid($size . $pct . $amount, true));
    $barColor = $done || $color === 'grass' ? 'var(--grass)' : 'var(--mango)';
@endphp

<div
    class="progress-ring {{ $done ? 'pr-done' : '' }} {{ $className }}"
    style="width: {{ $size }}px; height: {{ $size }}px;"
    role="img"
    aria-label="{{ $label ?: 'Progress' }} {{ number_format($pct) }}%"
    x-data="{ pct: {{ $animate ? 0 : $pct }}, target: {{ $pct }} }"
    x-init="
        $nextTick(() => {
            if (!{{ $animate ? 'true' : 'false' }}) { pct = target; return; }
            const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (reduced) { pct = target; return; }
            const obs = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        requestAnimationFrame(() => {
                            const start = performance.now();
                            const duration = 1000;
                            const tick = (now) => {
                                const t = Math.min(1, (now - start) / duration);
                                const ease = 1 - Math.pow(1 - t, 3);
                                pct = target * ease;
                                if (t < 1) requestAnimationFrame(tick);
                                else pct = target;
                            };
                            requestAnimationFrame(tick);
                        });
                        obs.disconnect();
                    }
                });
            }, { threshold: 0.3 });
            obs.observe($el);
        })
    "
>
    <svg width="{{ $size }}" height="{{ $size }}">
        <circle
            class="pr-track"
            cx="{{ $size / 2 }}"
            cy="{{ $size / 2 }}"
            r="{{ $radius }}"
            stroke-width="{{ $stroke }}"
        ></circle>
        <circle
            id="{{ $uid }}"
            class="pr-bar"
            cx="{{ $size / 2 }}"
            cy="{{ $size / 2 }}"
            r="{{ $radius }}"
            stroke-width="{{ $stroke }}"
            stroke-dasharray="{{ $circumference }}"
            :stroke-dashoffset="((1 - pct / 100) * {{ $circumference }})"
        ></circle>
    </svg>

    <div class="pr-center">
        @if($amount)
            <span class="pr-value" style="font-size: {{ max(11, $size * 0.14) }}px;">{{ $amount }}</span>
        @endif
        @if($label)
            <span class="pr-label">{{ $label }}</span>
        @endif
    </div>
</div>
