@props(['crumbs' => []])

<nav class="mb-4 flex items-center gap-1.5 text-xs" aria-label="Breadcrumb">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1 font-medium text-slate transition-colors hover:text-brand">
        <i class="bi bi-house-door-fill"></i>
    </a>
    @foreach($crumbs as $crumb)
        <i class="bi bi-chevron-right text-ink/25"></i>
        @if(!empty($crumb['route']))
            <a href="{{ route($crumb['route']) }}" class="font-medium text-slate transition-colors hover:text-brand">{{ $crumb['label'] }}</a>
        @else
            <span class="font-semibold text-ink">{{ $crumb['label'] }}</span>
        @endif
    @endforeach
</nav>
