{{--
    Horizontal tab nav for authenticated account pages.
    Marks the current route prefix with `active`.
--}}
@php
    $osAccountTabs = [
        ['label' => 'Profile', 'icon' => 'bi-person', 'url' => url('/profile'), 'match' => request()->is('profile')],
        ['label' => 'Orders', 'icon' => 'bi-bag', 'url' => url('/orders'), 'match' => request()->is('orders*')],
        ['label' => 'Wallet', 'icon' => 'bi-wallet2', 'url' => url('/wallet'), 'match' => request()->is('wallet*')],
        ['label' => 'Wishlist', 'icon' => 'bi-heart', 'url' => url('/wishlist'), 'match' => request()->is('wishlist*')],
        ['label' => 'Requests', 'icon' => 'bi-inbox', 'url' => url('/requests'), 'match' => request()->is('requests*')],
    ];
@endphp

<nav class="border-b border-ink/10 bg-white" aria-label="Account navigation">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="os-tabs -mb-px py-2">
            @foreach($osAccountTabs as $osTab)
                <a
                    href="{{ $osTab['url'] }}"
                    class="os-tab {{ $osTab['match'] ? 'os-tab-active' : '' }}"
                    @if($osTab['match']) aria-current="page" @endif
                >
                    <i class="bi {{ $osTab['icon'] }}"></i> {{ $osTab['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</nav>
