@php
    $promos = [
        '0% interest on every plan — pay weekly or monthly',
        'Free delivery on orders over '.currency().'50,000',
        '30-day easy exchange on everything you own',
        'No credit check. No hidden fees. Ever.',
    ];
@endphp

<div class="announce-bar" x-data="promoTicker(@js($promos))" role="region" aria-label="Announcements">
    <div class="mx-auto flex h-9 max-w-7xl items-center justify-center gap-2.5 px-4 sm:px-6">
        <i class="bi bi-megaphone-fill text-sm text-mango" aria-hidden="true"></i>
        <p class="truncate text-center text-xs font-semibold text-white/90 sm:text-[13px]" x-text="item" :key="index"></p>
    </div>
</div>
