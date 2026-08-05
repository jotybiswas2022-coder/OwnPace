{{--
    Empty state with a next-action CTA.
    Props: icon, title, message, actionLabel, actionUrl.
--}}
@props([
    'icon' => 'bi-inbox',
    'title' => 'Nothing here yet',
    'message' => null,
    'actionLabel' => null,
    'actionUrl' => null,
])

<div class="os-card flex flex-col items-center justify-center px-6 py-16 text-center">
    <span class="os-empty-icon"><i class="bi {{ $icon }}"></i></span>
    <h3 class="mt-5 font-display text-lg font-bold text-ink">{{ $title }}</h3>
    @if($message)
        <p class="mt-2 max-w-sm text-sm leading-relaxed text-slate">{{ $message }}</p>
    @endif
    @if($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}" class="os-btn os-btn-brand os-btn-sm mt-6"><i class="bi bi-arrow-right"></i> {{ $actionLabel }}</a>
    @endif
</div>
