@extends('frontend.layouts.store')
@section('title', 'FAQs — '.storeName())

@php
    $osFaqIcons = [
        'payments' => 'bi-coin',
        'delivery' => 'bi-truck',
        'insurance' => 'bi-shield-check',
        'orders' => 'bi-bag-check',
        'account' => 'bi-person',
    ];

    $osFaqGroups = ($faqs ?? collect())->map(function ($group, $category) use ($osFaqIcons) {
        return [
            'category' => $category ?: 'General',
            'icon' => $osFaqIcons[strtolower($category)] ?? 'bi-question-circle',
            'items' => $group->map(fn ($f) => [
                'id' => $f->id,
                'question' => $f->question,
                'answer' => $f->answer,
            ])->values(),
        ];
    })->values();

    $osFaqFallback = [
        ['category' => 'Payments & Plans', 'icon' => 'bi-coin', 'items' => [
            ['question' => 'How does '.storeName().' installment work?', 'answer' => storeName().' lets you purchase products and pay over time. Choose from weekly (4–40 weeks) or monthly (1–12 months) plans. Pay 70% upfront to get your item shipped, then complete the remaining balance in installments.'],
            ['question' => 'What payment methods do you accept?', 'answer' => 'We accept credit/debit cards, bank transfers, USSD, and wallet payments. We integrate with Paystack, Flutterwave, and Korapay for secure transactions.'],
            ['question' => 'Can I pay off my plan early?', 'answer' => 'Yes! You can pay your next installment before the due date, pay any specific amount of your choice, or pay off the entire balance at once with no early payment penalty.'],
            ['question' => 'Can I change my installment plan?', 'answer' => 'Absolutely! You can request to change your installment type or duration. Simply go to your orders page, request a plan change with a reason, and our admin team will review and approve it.'],
        ]],
        ['category' => 'Delivery & Shipping', 'icon' => 'bi-truck', 'items' => [
            ['question' => 'When will my item be delivered?', 'answer' => 'Your item will be shipped once you have paid at least 70% of the total order. Delivery times vary by location, typically 3–7 business days within major cities.'],
            ['question' => 'Can I track my delivery?', 'answer' => 'Yes! You can view your delivery timeline and tracking information from your orders page. We will also send you notifications when your item is ready to ship and when it is delivered.'],
            ['question' => 'Can someone else receive my delivery?', 'answer' => 'Yes, you can assign a proxy (a registered store user) to receive your delivery if you are unavailable. You can manage this during checkout or from your orders page.'],
        ]],
        ['category' => 'Insurance & Returns', 'icon' => 'bi-shield-check', 'items' => [
            ['question' => 'Can I insure my product?', 'answer' => 'Yes! You can add insurance to any product for a small percentage of the total order. This covers your product against damage, loss, or theft during the installment period.'],
            ['question' => 'Can I cancel my installment plan?', 'answer' => 'Yes, you can request to cancel your installment plan. A 10% cancellation charge applies and the remaining amount will be refunded to your wallet for future purchases.'],
            ['question' => 'Can I exchange my product?', 'answer' => 'Yes! You can request to exchange your product for one from your wishlist. Submit an exchange request with your reason, and our admin team will review and approve it.'],
        ]],
    ];
@endphp

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-4xl px-4 text-center sm:px-6">
        <span class="os-eyebrow justify-center"><i class="bi bi-question-circle-fill"></i> Help Center</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Frequently Asked Questions</h1>
        <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-slate sm:text-base">Everything you need to know about shopping with {{ storeName() }} — payments, delivery, insurance and more.</p>

        <div class="relative mx-auto mt-8 max-w-xl" x-data="faqBrowser(@json($osFaqGroups->isEmpty() ? $osFaqFallback : $osFaqGroups))">
            <i class="bi bi-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate" aria-hidden="true"></i>
            <input
                type="search"
                x-model="q"
                placeholder="Search FAQs… e.g. delivery, insurance, cancel"
                class="os-input w-full py-3.5 pl-11"
                aria-label="Search frequently asked questions"
            >
        </div>
    </div>
</section>

<section class="os-section" x-data="faqBrowser(@json($osFaqGroups->isEmpty() ? $osFaqFallback : $osFaqGroups))">
    <div class="mx-auto max-w-4xl px-4 sm:px-6">
        {{-- Category pills --}}
        <div class="flex flex-wrap justify-center gap-2" role="group" aria-label="Filter FAQs by category">
            <button
                type="button"
                class="os-chip border transition-colors hover:border-mango/50"
                :class="cat === 'all' ? 'bg-indigo text-white border-transparent' : 'bg-paper-deep/60 text-slate border-ink/10'"
                @click="cat = 'all'"
                :aria-pressed="cat === 'all'"
            >
                <i class="bi bi-grid-fill"></i> All <span class="font-mono text-xs opacity-70" x-text="allCount"></span>
            </button>
            <template x-for="g in groups" :key="g.category">
                <button
                    type="button"
                    class="os-chip border transition-colors hover:border-mango/50"
                    :class="cat === g.category ? 'bg-indigo text-white border-transparent' : 'bg-paper-deep/60 text-slate border-ink/10'"
                    @click="cat = g.category"
                    :aria-pressed="cat === g.category"
                >
                    <i class="bi" :class="g.icon"></i> <span x-text="g.category"></span>
                    <span class="font-mono text-xs opacity-70" x-text="g.items.length"></span>
                </button>
            </template>
        </div>

        {{-- FAQ groups --}}
        <div class="mt-10 space-y-10">
            <template x-for="g in filteredGroups()" :key="g.category">
                <div x-reveal="80">
                    <div class="mb-4 flex items-center gap-2.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-mango/15 text-mango-deep"><i class="bi" :class="g.icon"></i></span>
                        <h2 class="font-display text-lg font-bold text-ink" x-text="g.category"></h2>
                        <span class="os-hr flex-1 my-0"></span>
                    </div>
                    <div class="space-y-3" x-data="{ open: null }">
                        <template x-for="(item, idx) in g.items" :key="item.id">
                            <div class="os-card overflow-hidden">
                                <button
                                    type="button"
                                    class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left"
                                    :aria-expanded="open === idx"
                                    :aria-controls="'faq-panel-' + g.category.replace(/\W+/g, '-').toLowerCase() + '-' + item.id"
                                    @click="open = open === idx ? null : idx"
                                >
                                    <span class="flex items-center gap-3">
                                        <i class="bi bi-question-circle text-mango-deep" aria-hidden="true"></i>
                                        <span class="text-sm font-semibold text-ink" x-text="item.question"></span>
                                    </span>
                                    <i class="bi shrink-0 text-slate transition-transform duration-200" :class="open === idx ? 'bi-chevron-up rotate-180' : 'bi-chevron-down'" aria-hidden="true"></i>
                                </button>
                                <div
                                    x-show="open === idx"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    :id="'faq-panel-' + g.category.replace(/\W+/g, '-').toLowerCase() + '-' + item.id"
                                    class="border-t border-ink/5 px-5 py-4"
                                >
                                    <p class="text-sm leading-relaxed text-slate" x-text="item.answer"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        {{-- No results --}}
        <div x-cloak x-show="filteredGroups().length === 0" class="mx-auto mt-10 max-w-md">
            <div class="os-card flex flex-col items-center justify-center px-6 py-16 text-center">
                <span class="os-empty-icon"><i class="bi bi-search-heart"></i></span>
                <h3 class="mt-5 font-display text-lg font-bold text-ink">No results found</h3>
                <p class="mt-2 max-w-sm text-sm leading-relaxed text-slate">Try a different search term or browse by category above.</p>
                <button type="button" class="os-btn os-btn-brand os-btn-sm mt-6" @click="q = ''; cat = 'all'"><i class="bi bi-arrow-counterclockwise"></i> Clear search</button>
            </div>
        </div>

        {{-- Still stuck? --}}
        <div class="mt-14 text-center">
            <div class="os-card mx-auto max-w-lg p-8">
                <span class="os-empty-icon mx-auto"><i class="bi bi-headset"></i></span>
                <h3 class="mt-4 font-display text-lg font-bold text-ink">Still have questions?</h3>
                <p class="mt-2 text-sm text-slate">Our support team is ready to help you — usually within 24 hours.</p>
                <a href="{{ url('/contact') }}" class="os-btn os-btn-brand mt-6"><i class="bi bi-chat-dots-fill"></i> Contact Support</a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function faqBrowser(groups) {
    return {
        groups,
        q: '',
        cat: 'all',
        get allCount() {
            return this.groups.reduce((n, g) => n + g.items.length, 0);
        },
        filteredGroups() {
            const query = this.q.trim().toLowerCase();
            return this.groups
                .map((g) => ({
                    ...g,
                    items: g.items.filter((i) =>
                        !query
                        || i.question.toLowerCase().includes(query)
                        || i.answer.toLowerCase().includes(query)
                    ),
                }))
                .filter((g) => (this.cat === 'all' || g.category === this.cat) && g.items.length > 0);
        },
    };
}
</script>
@endpush
