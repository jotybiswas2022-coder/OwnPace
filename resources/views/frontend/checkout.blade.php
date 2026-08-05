@extends('frontend.layouts.store')
@section('title', 'Checkout — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-credit-card-fill"></i> Secure Checkout</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Complete your purchase</h1>
        <p class="mt-2 text-sm text-slate sm:text-base">You're just a few steps away from owning your items.</p>

        <ol class="mt-8 flex items-center gap-2 sm:gap-3" aria-label="Checkout steps">
            <li class="flex items-center gap-2" aria-current="step">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-mango font-mono text-sm font-bold text-ink">1</span>
                <span class="text-sm font-semibold text-ink">Delivery</span>
            </li>
            <li class="h-0.5 w-6 rounded bg-ink/15 sm:w-12" aria-hidden="true"></li>
            <li class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-paper-deep font-mono text-sm font-bold text-slate ring-1 ring-ink/15">2</span>
                <span class="text-sm font-semibold text-slate">Payment</span>
            </li>
            <li class="h-0.5 w-6 rounded bg-ink/15 sm:w-12" aria-hidden="true"></li>
            <li class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-paper-deep font-mono text-sm font-bold text-slate ring-1 ring-ink/15">3</span>
                <span class="text-sm font-semibold text-slate">Confirm</span>
            </li>
        </ol>
    </div>
</section>

<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6" x-data="checkoutPage(@json($termsByPlan ?? []))">
        @if($errors->any())
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-ember/30 bg-ember/5 p-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill mt-0.5 text-ember-deep"></i>
                <div class="text-sm text-ink">
                    <p class="font-semibold text-ember-deep">Please review the highlighted fields below before placing your order.</p>
                    <ul class="mt-1.5 list-inside list-disc space-y-0.5 text-slate">
                        @foreach($errors->all() as $osError)
                            <li>{{ $osError }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="grid gap-8 lg:grid-cols-5">
                <div class="space-y-6 lg:col-span-3">
                    {{-- ===== DELIVERY ADDRESS ===== --}}
                    <div class="os-card p-6 sm:p-7" x-reveal>
                        <div class="mb-5 flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-mango/15 text-mango-deep"><i class="bi bi-geo-alt-fill"></i></span>
                            <h2 class="font-display text-base font-bold text-ink">Delivery address</h2>
                        </div>

                        @if($addresses && $addresses->count() > 0)
                            <div class="space-y-3">
                                @foreach($addresses as $addr)
                                <label class="addr-card flex cursor-pointer items-start gap-4 rounded-xl border-2 p-4 transition-all has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-mango/50"
                                       :class="addressId == {{ $addr->id }} ? 'border-mango bg-mango/5' : 'border-ink/10 bg-paper-deep/40 hover:border-mango/40'">
                                    <input type="radio" name="delivery_address_id" value="{{ $addr->id }}" class="sr-only" x-model="addressId">
                                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 transition-colors"
                                          :class="addressId == {{ $addr->id }} ? 'border-mango bg-mango' : 'border-ink/20'">
                                        <span class="h-2 w-2 rounded-full bg-white" x-show="addressId == {{ $addr->id }}"></span>
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="flex flex-wrap items-center gap-2">
                                            <strong class="text-sm text-ink">{{ $addr->label ?? 'Address' }}</strong>
                                            @if($addr->is_default)
                                                <span class="os-chip os-chip-mango px-2 py-0.5 text-[10px]">Default</span>
                                            @endif
                                        </span>
                                        <span class="mt-0.5 block text-sm text-slate">{{ $addr->address_line1 }}, {{ $addr->city }}, {{ $addr->state }}</span>
                                        @if($addr->phone)
                                            <span class="mt-1 block text-xs text-slate/80"><i class="bi bi-telephone-fill"></i> {{ $addr->phone }}</span>
                                        @endif
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-xl border border-dashed border-ink/15 bg-paper-deep/40 p-8 text-center">
                                <i class="bi bi-geo-alt text-3xl text-ink/20"></i>
                                <p class="mt-3 text-sm text-slate">No saved addresses yet. Add one to continue — it only takes a minute.</p>
                            </div>
                        @endif
                        @error('delivery_address_id')<p class="os-error-text">{{ $message }}</p>@enderror

                        <button type="button" class="os-btn os-btn-ghost os-btn-sm mt-4" @click="modalOpen = true">
                            <i class="bi bi-plus-lg"></i> Add a new address
                        </button>

                        {{-- ===== DELIVERY PROXY ===== --}}
                        <div class="mt-6 border-t border-ink/10 pt-6">
                            <div class="mb-3 flex items-center gap-2">
                                <i class="bi bi-person-check text-mango-deep"></i>
                                <h3 class="font-display text-sm font-bold text-ink">Receive for someone else?</h3>
                            </div>
                            <p class="mb-3 text-xs text-slate">Assign another store member to receive this delivery on your behalf.</p>
                            <input type="hidden" name="delivery_proxy_user_id" id="deliveryProxyUserId" value="{{ old('delivery_proxy_user_id') }}">
                            <div class="flex gap-2">
                                <input type="text" id="proxySearchInput" placeholder="Search by phone, email or name" class="os-input" aria-label="Search for a delivery proxy">
                                <button type="button" class="os-btn os-btn-brand os-btn-sm shrink-0" id="proxySearchBtn"><i class="bi bi-search"></i> Find</button>
                            </div>
                            <div id="proxyResults" class="mt-3 space-y-2"></div>
                            <div id="proxyAssigned" class="mt-3 hidden">
                                <div class="flex items-center gap-3 rounded-xl border border-grass/30 bg-grass/5 p-3 text-sm">
                                    <i class="bi bi-person-check-fill text-grass-deep"></i>
                                    <p class="flex-1 text-ink"><strong id="proxyAssignedName"></strong> <span class="text-slate">will receive your delivery</span></p>
                                    <a href="#" id="proxyClear" class="text-xs font-semibold text-ember-deep hover:underline">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===== PAYMENT METHOD ===== --}}
                    <div class="os-card p-6 sm:p-7" x-reveal="60">
                        <div class="mb-5 flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-mango/15 text-mango-deep"><i class="bi bi-coin"></i></span>
                            <h2 class="font-display text-base font-bold text-ink">Payment method</h2>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 p-4 transition-all has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-mango/50"
                                   :class="paymentType === 'full' ? 'border-mango bg-mango/5' : 'border-ink/10 bg-paper-deep/40 hover:border-mango/40'">
                                <input type="radio" name="payment_type" value="full" class="sr-only" x-model="paymentType">
                                <i class="bi bi-cash-stack text-xl" :class="paymentType === 'full' ? 'text-mango-deep' : 'text-slate'"></i>
                                <span>
                                    <strong class="block text-sm text-ink">Pay in full</strong>
                                    <small class="block text-xs text-slate">One-time payment</small>
                                </span>
                            </label>
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 p-4 transition-all has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-mango/50"
                                   :class="paymentType === 'installment' ? 'border-mango bg-mango/5' : 'border-ink/10 bg-paper-deep/40 hover:border-mango/40'">
                                <input type="radio" name="payment_type" value="installment" class="sr-only" x-model="paymentType">
                                <i class="bi bi-calendar-check text-xl" :class="paymentType === 'installment' ? 'text-mango-deep' : 'text-slate'"></i>
                                <span>
                                    <strong class="block text-sm text-ink">Pay in installments</strong>
                                    <small class="block text-xs text-slate">Flexible plans</small>
                                </span>
                            </label>
                        </div>
                        @error('payment_type')<p class="os-error-text">{{ $message }}</p>@enderror

                        <div x-cloak x-show="paymentType === 'installment'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                            <livewire:checkout-breakdown
                                :subtotal="(float) max($total - $discount, 0)"
                                :shipping-fee="(float) $shippingFee"
                                wire:key="checkout-breakdown"
                            />
                        </div>
                    </div>

                    {{-- ===== EXTRAS ===== --}}
                    <div class="os-card flex items-start gap-3 p-6" x-reveal="120">
                        <i class="bi bi-shield-check mt-0.5 text-xl text-grass-deep"></i>
                        <p class="text-sm text-slate">Add <strong class="text-ink">insurance</strong> on the installment plan above to protect your purchase against damage, loss, or theft.</p>
                    </div>

                    {{-- ===== PAY USING ===== --}}
                    <div class="os-card p-6 sm:p-7" x-reveal="180">
                        <div class="mb-5 flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo/10 text-brand"><i class="bi bi-credit-card-2-front"></i></span>
                            <h2 class="font-display text-base font-bold text-ink">Pay using</h2>
                        </div>

                        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                            <label class="flex cursor-pointer flex-col items-center rounded-xl border-2 p-4 text-center transition-all has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-mango/50"
                                   :class="method === 'wallet' ? 'border-mango bg-mango/5' : 'border-ink/10 bg-paper-deep/40 hover:border-mango/40'">
                                <input type="radio" name="payment_method" value="wallet" class="sr-only" x-model="method">
                                <i class="bi bi-wallet2 text-2xl" :class="method === 'wallet' ? 'text-mango-deep' : 'text-slate'"></i>
                                <strong class="mt-2 block text-sm text-ink">Wallet</strong>
                                <small class="mt-0.5 block text-xs text-slate">₦{{ number_format($wallet->balance ?? 0, 0) }}</small>
                            </label>
                            <label class="flex cursor-pointer flex-col items-center rounded-xl border-2 p-4 text-center transition-all has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-mango/50"
                                   :class="method === 'paystack' ? 'border-mango bg-mango/5' : 'border-ink/10 bg-paper-deep/40 hover:border-mango/40'">
                                <input type="radio" name="payment_method" value="paystack" class="sr-only" x-model="method">
                                <i class="bi bi-credit-card-fill text-2xl" :class="method === 'paystack' ? 'text-mango-deep' : 'text-slate'"></i>
                                <strong class="mt-2 block text-sm text-ink">Paystack</strong>
                                <small class="mt-0.5 block text-xs text-slate">Card, Bank, USSD</small>
                            </label>
                            <label class="flex cursor-pointer flex-col items-center rounded-xl border-2 p-4 text-center transition-all has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-mango/50"
                                   :class="method === 'flutterwave' ? 'border-mango bg-mango/5' : 'border-ink/10 bg-paper-deep/40 hover:border-mango/40'">
                                <input type="radio" name="payment_method" value="flutterwave" class="sr-only" x-model="method">
                                <i class="bi bi-globe2 text-2xl" :class="method === 'flutterwave' ? 'text-mango-deep' : 'text-slate'"></i>
                                <strong class="mt-2 block text-sm text-ink">Flutterwave</strong>
                                <small class="mt-0.5 block text-xs text-slate">Card, Bank, Mobile Money</small>
                            </label>
                            <label class="flex cursor-pointer flex-col items-center rounded-xl border-2 p-4 text-center transition-all has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-mango/50"
                                   :class="method === 'korapay' ? 'border-mango bg-mango/5' : 'border-ink/10 bg-paper-deep/40 hover:border-mango/40'">
                                <input type="radio" name="payment_method" value="korapay" class="sr-only" x-model="method">
                                <i class="bi bi-shield-fill-check text-2xl" :class="method === 'korapay' ? 'text-mango-deep' : 'text-slate'"></i>
                                <strong class="mt-2 block text-sm text-ink">Korapay</strong>
                                <small class="mt-0.5 block text-xs text-slate">Card, Transfer, USSD</small>
                            </label>
                        </div>
                        @error('payment_method')<p class="os-error-text">{{ $message }}</p>@enderror
                    </div>

                    {{-- ===== TERMS ===== --}}
                    <div class="rounded-xl border border-ink/10 bg-paper-deep/40 p-5" x-reveal="240">
                        <label class="flex cursor-pointer items-start gap-3">
                            <input type="checkbox" name="agree_terms" value="1" required class="mt-0.5 h-5 w-5 shrink-0 accent-mango">
                            <span class="text-sm leading-relaxed text-slate">
                                I agree to the <a href="{{ url('/terms') }}" target="_blank" rel="noopener" class="font-semibold text-brand underline underline-offset-2">Terms &amp; Conditions</a> and
                                <a href="{{ url('/terms/privacy') }}" target="_blank" rel="noopener" class="font-semibold text-brand underline underline-offset-2">Privacy Policy</a>.
                            </span>
                        </label>
                        <div id="planTermsNote" x-cloak class="mt-2 pl-8 text-xs text-slate">
                            <i class="bi bi-file-earmark-text-fill text-mango-deep"></i>
                            Also bound by: <a id="planTermsLink" href="#" target="_blank" rel="noopener" class="font-semibold text-brand underline underline-offset-2"></a>
                        </div>
                        @error('agree_terms')<p class="os-error-text">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- ===== ORDER SUMMARY ===== --}}
                <div class="lg:col-span-2 lg:sticky lg:top-24 lg:self-start" x-reveal="120">
                    <div class="os-card p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="font-display text-base font-bold text-ink">Order summary</h2>
                            <span class="text-xs font-semibold text-slate">{{ count($cart) }} {{ count($cart) === 1 ? 'item' : 'items' }}</span>
                        </div>

                        <div class="max-h-80 space-y-4 overflow-y-auto pr-1">
                            @foreach($cart as $item)
                            <div class="flex items-center gap-3">
                                @if($item['thumbnail'])
                                    <img src="{{ asset('storage/'.$item['thumbnail']) }}" alt="{{ $item['name'] }}" class="h-13 w-13 shrink-0 rounded-lg object-cover ring-1 ring-ink/10" style="width:52px;height:52px;" loading="lazy" decoding="async">
                                @else
                                    <span class="flex h-13 w-13 shrink-0 items-center justify-center rounded-lg bg-paper-deep text-ink/20" style="width:52px;height:52px;"><i class="bi bi-image"></i></span>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-ink">{{ $item['name'] }}</p>
                                    <p class="text-xs text-slate">Qty: {{ $item['quantity'] }}</p>
                                </div>
                                <p class="os-price text-sm">₦{{ number_format($item['price'] * $item['quantity'], 0) }}</p>
                            </div>
                            @endforeach
                        </div>

                        {{-- Promo --}}
                        <div class="mt-5">
                            @if($promoCode ?? null)
                                <div class="flex items-center gap-2.5 rounded-lg border border-grass/30 bg-grass/5 p-3 text-sm">
                                    <i class="bi bi-tag-fill text-grass-deep"></i>
                                    <span class="font-mono font-semibold text-ink">{{ $promoCode->code }}</span>
                                    <span class="os-price text-grass-deep">-₦{{ number_format($discount, 0) }}</span>
                                    <a href="{{ route('checkout.remove-promo') }}" class="ml-auto text-xs font-semibold text-ember-deep hover:underline">Remove</a>
                                </div>
                            @else
                                <form action="{{ route('checkout.apply-promo') }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="text" name="code" placeholder="Promo code" class="os-input" aria-label="Promo code">
                                    <button type="submit" class="os-btn os-btn-ghost os-btn-sm shrink-0">Apply</button>
                                </form>
                            @endif
                        </div>

                        <div class="os-hr"></div>

                        <dl class="space-y-2.5 text-sm">
                            <div class="flex items-center justify-between">
                                <dt class="text-slate">Subtotal</dt>
                                <dd class="os-price text-ink">₦{{ number_format($total, 0) }}</dd>
                            </div>
                            @if($discount > 0)
                            <div class="flex items-center justify-between">
                                <dt class="text-slate">Discount</dt>
                                <dd class="os-price text-grass-deep">-₦{{ number_format($discount, 0) }}</dd>
                            </div>
                            @endif
                            <div class="flex items-center justify-between">
                                <dt class="text-slate">Delivery fee</dt>
                                <dd class="os-price text-ink">₦{{ number_format((float) $shippingFee, 2) }}</dd>
                            </div>
                            <div class="os-hr my-3"></div>
                            <div class="flex items-center justify-between">
                                <dt class="font-display text-base font-bold text-ink">Total</dt>
                                <dd class="os-price text-lg font-bold text-brand">₦{{ number_format(max($total - $discount + $shippingFee, 0), 0) }}</dd>
                            </div>
                        </dl>

                        <p class="mt-4 rounded-lg border border-dashed border-mango/40 bg-mango/5 p-3 text-xs leading-relaxed text-slate">
                            <i class="bi bi-truck-front-fill text-mango-deep"></i>
                            Your delivery fee is included here and covered within your first 70% of payments — never charged as a separate line later.
                        </p>

                        <button type="submit" class="os-btn os-btn-mango mt-5 w-full py-3.5 text-base" id="placeOrderBtn">
                            <i class="bi bi-shield-lock-fill"></i> Place order
                        </button>

                        <div class="mt-5 grid grid-cols-3 gap-2 text-center text-[11px] font-medium text-slate">
                            <span class="rounded-lg bg-paper-deep/60 p-2.5"><i class="bi bi-lock-fill mr-1 text-brand"></i>Secure SSL</span>
                            <span class="rounded-lg bg-paper-deep/60 p-2.5"><i class="bi bi-shield-fill-check mr-1 text-grass-deep"></i>Encrypted</span>
                            <span class="rounded-lg bg-paper-deep/60 p-2.5"><i class="bi bi-arrow-repeat mr-1 text-mango-ink"></i>Easy returns</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- ===== ADD-ADDRESS MODAL (Alpine) ===== --}}
        <div x-cloak x-show="modalOpen" x-transition.opacity class="fixed inset-0 z-[90] flex items-end justify-center bg-ink/60 p-0 backdrop-blur-sm sm:items-center sm:p-6" role="dialog" aria-modal="true" aria-label="Add delivery address" @keydown.escape.window="modalOpen = false">
            <div class="w-full max-w-lg rounded-t-2xl bg-white p-6 shadow-lift sm:rounded-2xl sm:p-7" @click.outside="modalOpen = false">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="font-display text-lg font-bold text-ink"><i class="bi bi-geo-alt-fill mr-2 text-mango-deep"></i> Add delivery address</h3>
                    <button type="button" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate transition-colors hover:bg-paper-deep hover:text-ink" @click="modalOpen = false" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('profile.addresses.store') }}" class="grid gap-4 sm:grid-cols-2">
                    @csrf
                    <div>
                        <label for="os-addr-recipient" class="os-label">Recipient name</label>
                        <input id="os-addr-recipient" type="text" name="recipient_name" class="os-input" placeholder="e.g. Ada Obi" required>
                    </div>
                    <div>
                        <label for="os-addr-label" class="os-label">Label</label>
                        <input id="os-addr-label" type="text" name="label" class="os-input" placeholder="Home, Office…">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="os-addr-line1" class="os-label">Street address</label>
                        <input id="os-addr-line1" type="text" name="address_line1" class="os-input" placeholder="12 Marina Road, Phase 2" required>
                    </div>
                    <div>
                        <label for="os-addr-city" class="os-label">City</label>
                        <input id="os-addr-city" type="text" name="city" class="os-input" placeholder="Lagos" required>
                    </div>
                    <div>
                        <label for="os-addr-state" class="os-label">State</label>
                        <input id="os-addr-state" type="text" name="state" class="os-input" placeholder="Lagos" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="os-addr-phone" class="os-label">Phone</label>
                        <input id="os-addr-phone" type="tel" name="phone" class="os-input" placeholder="0801 234 5678" required>
                    </div>
                    <div class="flex items-center justify-end gap-3 sm:col-span-2">
                        <button type="button" class="os-btn os-btn-ghost" @click="modalOpen = false">Cancel</button>
                        <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Save address</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function checkoutPage(planTerms) {
    return {
        planTerms: planTerms || {},
        paymentType: {{ old('payment_type', 'full') === 'installment' ? '1' : '0' }} ? 'installment' : 'full',
        method: '{{ old('payment_method', 'paystack') }}',
        addressId: null,
        modalOpen: false,
        init() {
            const checked = document.querySelector('input[name="delivery_address_id"]:checked');
            if (checked) this.addressId = parseInt(checked.value, 10);
            else {
                const first = document.querySelector('input[name="delivery_address_id"]');
                if (first) { this.addressId = parseInt(first.value, 10); }
            }
        },
    };
}

// ===== PLAN-SCOPED TERMS: surface the selected plan's T&C before checkout =====
(function () {
    const planTerms = @json($termsByPlan ?? []);
    const note = document.getElementById('planTermsNote');
    const link = document.getElementById('planTermsLink');
    if (!note) return;

    function update(planId) {
        const entry = planTerms[planId];
        if (entry) {
            link.textContent = entry.title;
            link.href = entry.url;
            note.style.display = 'block';
        } else {
            note.style.display = 'none';
        }
    }

    Livewire.on('checkout-plan-changed', ({ planId }) => update(planId));
})();

// ===== PLACE ORDER: disable + show progress while submitting =====
document.getElementById('checkoutForm')?.addEventListener('submit', function (e) {
    const btn = document.getElementById('placeOrderBtn');
    if (btn.disabled) { e.preventDefault(); return; }
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Processing…';
});

// ===== DELIVERY PROXY: search a registered user, confirm, assign =====
(function () {
    const searchInput = document.getElementById('proxySearchInput');
    const searchBtn = document.getElementById('proxySearchBtn');
    const results = document.getElementById('proxyResults');
    const assigned = document.getElementById('proxyAssigned');
    const assignedName = document.getElementById('proxyAssignedName');
    const hidden = document.getElementById('deliveryProxyUserId');
    const clearBtn = document.getElementById('proxyClear');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    async function search() {
        const q = searchInput.value.trim();
        if (q.length < 3) {
            results.innerHTML = '<p class="text-xs text-slate">Type at least 3 characters to search.</p>';
            return;
        }
        searchBtn.disabled = true;
        searchBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i>';
        try {
            const res = await fetch('/checkout/proxy/search?q=' + encodeURIComponent(q), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf },
            });
            const data = await res.json();
            renderResults(data.users || []);
        } catch (e) {
            results.innerHTML = '<p class="text-xs font-semibold text-ember-deep">Could not search. Try again.</p>';
        } finally {
            searchBtn.disabled = false;
            searchBtn.innerHTML = '<i class="bi bi-search"></i> Find';
        }
    }

    function renderResults(users) {
        if (!users.length) {
            results.innerHTML = '<p class="text-xs text-slate">No matching store members found.</p>';
            return;
        }
        results.innerHTML = users.map(u => `
            <div class="flex items-center gap-3 rounded-lg border border-ink/10 bg-paper-deep/40 p-3">
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-ink">${esc(u.name)}</p>
                    <p class="truncate text-xs text-slate">${esc(u.email || '')}${u.phone ? ' · ' + esc(u.phone) : ''}</p>
                </div>
                <button type="button" class="os-btn os-btn-mango os-btn-sm shrink-0" data-id="${u.id}" data-name="${escAttr(u.name)}">Assign</button>
            </div>
        `).join('');
        results.querySelectorAll('button[data-id]').forEach(btn => {
            btn.addEventListener('click', () => confirmAssign(btn.dataset.id, btn.dataset.name));
        });
    }

    function confirmAssign(id, name) {
        const ok = window.confirm('Assign ' + name + ' to receive this delivery on your behalf?');
        if (!ok) return;
        hidden.value = id;
        assignedName.textContent = name;
        assigned.classList.remove('hidden');
        results.innerHTML = '';
        searchInput.value = '';
    }

    function esc(s) {
        return String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    }
    function escAttr(s) {
        return esc(s).replace(/"/g, '&quot;');
    }

    searchBtn?.addEventListener('click', search);
    searchInput?.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); search(); } });
    clearBtn?.addEventListener('click', e => {
        e.preventDefault();
        hidden.value = '';
        assigned.classList.add('hidden');
    });
})();
</script>
@endpush
