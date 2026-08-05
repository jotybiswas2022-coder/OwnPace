@extends('frontend.layouts.store')
@section('title', 'Order #'.$order->id.' — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <nav class="flex flex-wrap items-center gap-2 text-sm text-slate" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="font-medium text-brand transition-colors hover:text-brand-deep">Home</a>
            <i class="bi bi-chevron-right text-xs" aria-hidden="true"></i>
            <a href="{{ route('orders.index') }}" class="font-medium text-brand transition-colors hover:text-brand-deep">Orders</a>
            <i class="bi bi-chevron-right text-xs" aria-hidden="true"></i>
            <span class="font-semibold text-ink">Order #{{ $order->id }}</span>
        </nav>
        <div class="mt-4 flex flex-wrap items-end justify-between gap-4">
            <div>
                <span class="os-eyebrow"><i class="bi bi-receipt"></i> Order details</span>
                <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Order #{{ $order->id }}</h1>
                <p class="mt-2 text-sm text-slate">Placed {{ $order->created_at->format('M d, Y') }}</p>
            </div>
            <span class="os-chip {{ in_array($order->status, ['completed', 'fully_paid']) ? 'os-chip-grass' : (in_array($order->status, ['cancelled', 'failed']) ? 'os-chip-ember' : 'os-chip-mango') }}">
                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
            </span>
        </div>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="grid gap-8 lg:grid-cols-3">

            {{-- ===== LEFT COLUMN ===== --}}
            <div class="space-y-6 lg:col-span-2">

                {{-- Payment progress --}}
                <div class="os-card p-6 sm:p-8" x-reveal>
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-mango/15 text-lg text-mango-deep"><i class="bi bi-graph-up-arrow"></i></span>
                        <div>
                            <h2 class="font-display text-base font-bold text-ink">Your journey to ownership</h2>
                            <p class="text-xs text-slate">{{ $progressLabel }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col items-center text-center">
                        <x-progress-ring
                            :percentage="$progressPct"
                            :amount="'₦' . number_format((float) $order->remaining_amount, 0)"
                            :label="$progressPct >= 100 ? 'paid off' : 'left to own'"
                            :size="200"
                            :stroke="13"
                        />

                        @if($order->isEligibleForShipping() && $order->delivery_status !== 'delivered')
                        <div class="mt-6 flex w-full max-w-sm items-center gap-3 rounded-xl border border-grass/30 bg-grass/5 p-4 text-left">
                            <i class="bi bi-truck-front-fill shrink-0 text-2xl text-grass-deep" aria-hidden="true"></i>
                            <div>
                                <p class="text-sm font-semibold text-grass-deep">Your item can be shipped here</p>
                                <p class="text-xs text-slate">You've reached {{ \App\Services\DeliveryStatusService::thresholdPercent() }}% paid — delivery is unlocked.</p>
                            </div>
                        </div>
                        @endif

                        <div class="mt-6 grid w-full max-w-sm grid-cols-3 gap-3">
                            <div class="rounded-xl bg-paper-deep/60 p-3">
                                <p class="font-mono text-sm font-semibold text-ink">₦{{ number_format((float) $order->paid_amount, 0) }}</p>
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate">Paid</p>
                            </div>
                            <div class="rounded-xl bg-paper-deep/60 p-3">
                                <p class="font-mono text-sm font-semibold text-ink">₦{{ number_format((float) $order->remaining_amount, 0) }}</p>
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate">Remaining</p>
                            </div>
                            @if($order->installmentPlan)
                            <div class="rounded-xl bg-paper-deep/60 p-3">
                                <p class="truncate text-sm font-semibold text-ink">{{ $order->installmentPlan->name }}</p>
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate">{{ $order->installmentPlan->cadence }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Installment schedule --}}
                @if($order->installmentPlan && $order->installmentPayments->count() > 0)
                <div class="os-card overflow-hidden" x-reveal="80">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/10 px-5 py-4">
                        <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-calendar-check text-mango-deep"></i> Payment schedule</h3>
                        @php
                            $paidCount = $order->installmentPayments->where('status', 'paid')->count();
                            $totalCount = $order->installmentPayments->count();
                        @endphp
                        <span class="text-xs font-semibold text-slate">{{ $paidCount }} of {{ $totalCount }} paid</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="os-table w-full">
                            <thead>
                                <tr>
                                    <th>Installment</th>
                                    <th>Due date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->installmentPayments->sortBy('installment_number') as $ip)
                                @php
                                    $isNext = $nextPayment && $nextPayment->id === $ip->id;
                                    $lateFee = $ip->is_overdue ? \App\Services\InstallmentScheduleService::lateFeeFor($ip) : 0;
                                @endphp
                                <tr class="{{ $isNext ? 'bg-mango/5' : '' }}">
                                    <td data-label="Installment">
                                        <span class="font-mono text-sm font-semibold text-ink">#{{ $ip->installment_number }}</span>
                                    </td>
                                    <td data-label="Due date">
                                        <span class="text-sm text-slate">{{ $ip->due_date->format('M d, Y') }}</span>
                                        @if($ip->is_overdue)
                                            <span class="os-chip os-chip-ember ml-1 px-2 py-0.5 text-[10px]"><i class="bi bi-exclamation-triangle-fill"></i> Late</span>
                                        @endif
                                    </td>
                                    <td data-label="Amount">
                                        <span class="os-price text-sm">₦{{ number_format((float) $ip->amount, 2) }}</span>
                                        @if($ip->late_fee > 0 || $lateFee > 0)
                                            <p class="text-[11px] font-semibold text-ember-deep">incl. ₦{{ number_format((float) ($ip->late_fee ?: $lateFee), 2) }} late fee</p>
                                        @endif
                                    </td>
                                    <td data-label="Status">
                                        @if($ip->status === 'paid')
                                            <span class="os-chip os-chip-grass"><i class="bi bi-check-circle-fill"></i> Paid</span>
                                        @elseif($ip->is_overdue)
                                            <span class="os-chip os-chip-ember"><i class="bi bi-clock-fill"></i> Overdue</span>
                                        @else
                                            <span class="os-chip os-chip-slate">Upcoming</span>
                                        @endif
                                    </td>
                                    <td data-label="Action" class="justify-end">
                                        @if($isNext)
                                            <form action="{{ route('orders.pay.installment', $order) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="installment_payment_id" value="{{ $ip->id }}">
                                                <button type="submit" class="os-btn os-btn-mango os-btn-sm"><i class="bi bi-coin"></i> Pay now</button>
                                            </form>
                                        @elseif($ip->status !== 'paid')
                                            <span class="text-xs text-slate">Due later</span>
                                        @else
                                            <span class="text-grass-deep"><i class="bi bi-check2"></i></span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- Order details --}}
                <div class="os-card p-6" x-reveal="120">
                    <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-receipt text-mango-deep"></i> Order details</h3>
                    <dl class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <dt class="os-label mb-1">Order date</dt>
                            <dd class="text-sm font-medium text-ink">{{ $order->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="os-label mb-1">Total amount</dt>
                            <dd class="os-price text-sm">₦{{ number_format((float) $order->grand_total, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="os-label mb-1">Paid amount</dt>
                            <dd class="os-price text-sm text-grass-deep">₦{{ number_format((float) $order->paid_amount, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="os-label mb-1">Remaining</dt>
                            <dd class="os-price text-sm">₦{{ number_format((float) $order->remaining_amount, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="os-label mb-1">Payment plan</dt>
                            <dd class="text-sm font-medium text-ink">{{ $order->installmentPlan?->name ?? ($order->payment_type === 'full' ? 'Paid in full' : '—') }}</dd>
                        </div>
                        <div>
                            <dt class="os-label mb-1">Delivery status</dt>
                            <dd class="text-sm font-medium text-ink">{{ ucfirst(str_replace('_', ' ', $order->delivery_status ?? 'pending')) }}</dd>
                        </div>
                        @if($order->deliveryProxyUser)
                        <div>
                            <dt class="os-label mb-1">Delivery proxy</dt>
                            <dd class="text-sm font-medium text-ink">{{ $order->deliveryProxyUser->name }}<span class="block text-xs text-slate">{{ $order->deliveryProxyUser->phone ?? $order->deliveryProxyUser->email }}</span></dd>
                        </div>
                        @endif
                    </dl>
                    @if($order->installmentPlan && $order->interest_amount > 0)
                    <div class="mt-5 rounded-lg bg-paper-deep/60 p-3 text-xs text-slate">
                        <i class="bi bi-info-circle-fill text-mango-deep"></i>
                        Includes ₦{{ number_format((float) $order->interest_amount, 2) }} interest
                        @if($order->has_insurance) · ₦{{ number_format((float) $order->insurance_fee, 2) }} insurance @endif
                        @if((float) $order->shipping_fee > 0) · ₦{{ number_format((float) $order->shipping_fee, 2) }} shipping @endif
                    </div>
                    @endif
                </div>

                {{-- Delivery tracking timeline --}}
                @if($order->deliveryTrackings && $order->deliveryTrackings->count() > 0)
                <div class="os-card p-6" x-reveal="160">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-truck-front-fill text-mango-deep"></i> Delivery tracking</h3>
                        @php
                            $dStatus = $order->delivery_status ?? 'pending';
                        @endphp
                        <span class="os-chip {{ $dStatus === 'delivered' ? 'os-chip-grass' : ($dStatus === 'eligible' ? 'os-chip-grass' : 'os-chip-mango') }}">
                            <i class="bi {{ $dStatus === 'delivered' ? 'bi-check-circle-fill' : 'bi-truck' }}"></i> {{ ucfirst(str_replace('_', ' ', $dStatus)) }}
                        </span>
                    </div>

                    @php
                        $steps = [
                            ['key' => 'processing', 'icon' => 'bi-gear-fill', 'label' => 'Processing', 'desc' => 'Your order is being prepared'],
                            ['key' => 'shipped', 'icon' => 'bi-truck-front-fill', 'label' => 'Shipped', 'desc' => 'On its way to you'],
                            ['key' => 'delivered', 'icon' => 'bi-check-circle-fill', 'label' => 'Delivered', 'desc' => 'Handed over to you'],
                        ];
                        $current = $order->delivery_status ?? 'pending';
                        $reached = ['processing' => $current !== 'pending', 'shipped' => in_array($current, ['shipped','in_transit','out_for_delivery','delivered','failed']), 'delivered' => in_array($current, ['delivered']) || ($current === 'failed')];
                    @endphp
                    <div class="mt-6 flex items-start">
                        @foreach($steps as $i => $st)
                        <div class="relative flex-1 text-center">
                            @if($i < count($steps) - 1)
                            <div class="absolute left-1/2 right-[-50%] top-[19px] h-0.5" style="background:{{ $reached[$steps[$i+1]['key']] ? 'var(--grass)' : 'rgba(26,27,35,0.12)' }};" aria-hidden="true"></div>
                            @endif
                            <span class="relative mx-auto flex h-10 w-10 items-center justify-center rounded-full text-base ring-1"
                                  :class="$store?.none"
                                  style="background:{{ $reached[$st['key']] ? 'var(--mango)' : 'var(--paper-deep)' }};color:{{ $reached[$st['key']] ? 'var(--ink)' : 'var(--slate)' }};border-color:{{ $reached[$st['key']] ? 'transparent' : 'rgba(26,27,35,0.15)' }};box-shadow:{{ $current === $st['key'] ? '0 0 0 5px rgba(245,166,35,0.18)' : 'none' }};">
                                <i class="bi {{ $st['icon'] }}"></i>
                            </span>
                            <p class="mt-2 text-xs font-bold text-ink">{{ $st['label'] }}</p>
                            <p class="text-[10px] text-slate">{{ $reached[$st['key']] ? $st['desc'] : 'Pending' }}</p>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 space-y-4 border-t border-ink/5 pt-5">
                        @foreach($order->deliveryTrackings->sortByDesc('tracked_at') as $dt)
                        <div class="flex items-start gap-3">
                            <span class="mt-1.5 h-3 w-3 shrink-0 rounded-full {{ $dt->status === 'delivered' || $dt->status === 'eligible' ? 'bg-grass' : ($dt->status === 'shipped' ? 'bg-brand' : 'bg-ink/15') }}" aria-hidden="true"></span>
                            <div>
                                <p class="text-sm font-semibold text-ink">{{ $dt->description }}</p>
                                <p class="text-xs text-slate">{{ ($dt->tracked_at ?? $dt->created_at)->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Post-delivery review --}}
                @if($order->delivery_status === 'delivered' && !$deliveryReviewDone)
                <div class="os-card border-grass/30 p-6" x-reveal="200">
                    <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-stars text-grass-deep"></i> How was your delivery?</h3>
                    <form action="{{ route('orders.review', $order) }}" method="POST" class="mt-5 grid gap-5 sm:grid-cols-2">
                        @csrf
                        <div>
                            <span class="os-label">Delivery person</span>
                            <div class="fp-star-input mt-1" data-target="delivery_rating" role="radiogroup" aria-label="Rate the delivery person">
                                @for($s = 1; $s <= 5; $s++)
                                <i class="bi bi-star text-2xl" data-star="{{ $s }}" tabindex="0" role="radio" aria-label="{{ $s }} stars" style="cursor:pointer;color:rgba(26,27,35,0.2);transition:all 0.15s;"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="delivery_rating" id="delivery_rating" value="5">
                        </div>
                        <div>
                            <span class="os-label">Product satisfaction</span>
                            <div class="fp-star-input mt-1" data-target="product_rating" role="radiogroup" aria-label="Rate the product">
                                @for($s = 1; $s <= 5; $s++)
                                <i class="bi bi-star text-2xl" data-star="{{ $s }}" tabindex="0" role="radio" aria-label="{{ $s }} stars" style="cursor:pointer;color:rgba(26,27,35,0.2);transition:all 0.15s;"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="product_rating" id="product_rating" value="5">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="os-review-comment" class="os-label">Comments (optional)</label>
                            <input id="os-review-comment" type="text" name="delivery_comment" class="os-input" placeholder="Anything to share about the delivery or the product?">
                        </div>
                        <div class="sm:col-span-2">
                            <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-stars"></i> Submit feedback</button>
                        </div>
                    </form>
                </div>
                @endif

                {{-- Order items --}}
                <div class="os-card overflow-hidden" x-reveal="240">
                    <h3 class="flex items-center gap-2 border-b border-ink/10 px-5 py-4 font-display text-sm font-bold text-ink"><i class="bi bi-box-seam-fill text-mango-deep"></i> Order items</h3>
                    <div class="divide-y divide-ink/5">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-4 px-5 py-4">
                            @if($item->product && $item->product->primaryImage)
                                <img src="{{ asset('storage/'.$item->product->primaryImage->image_path) }}" alt="{{ $item->product->name }}" loading="lazy" class="h-13 w-13 shrink-0 rounded-lg object-cover ring-1 ring-ink/10" style="width:52px;height:52px;">
                            @else
                                <span class="flex h-13 w-13 shrink-0 items-center justify-center rounded-lg bg-paper-deep text-ink/20" style="width:52px;height:52px;"><i class="bi bi-image"></i></span>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-ink">{{ $item->product?->name ?? $item->product_name ?? 'Product' }}</p>
                                <p class="text-xs text-slate">Qty: {{ $item->quantity }} × ₦{{ number_format((float) $item->unit_price, 0) }}</p>
                            </div>
                            <span class="os-price text-sm">₦{{ number_format((float) $item->quantity * (float) $item->unit_price, 0) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ===== RIGHT COLUMN ===== --}}
            <div class="space-y-6 lg:sticky lg:top-24 lg:self-start" x-reveal="120" x-data="{ open: false, reason: '' }">
                {{-- Make a payment --}}
                <div class="os-card p-6">
                    <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-credit-card-fill text-mango-deep"></i> Make a payment</h3>
                    <div class="mt-5 space-y-4">
                        @if($order->remaining_amount > 0)
                            @if($nextPayment)
                            <form action="{{ route('orders.pay.installment', $order) }}" method="POST">
                                @csrf
                                <input type="hidden" name="installment_payment_id" value="{{ $nextPayment->id }}">
                                <button type="submit" class="os-btn os-btn-mango w-full py-3">
                                    <i class="bi bi-coin"></i> Pay next installment
                                </button>
                                <p class="mt-2 text-center text-xs text-slate">
                                    Installment #{{ $nextPayment->installment_number }} · <span class="os-price">₦{{ number_format($nextDue, 2) }}</span>
                                    @if($nextLateFee > 0) <span class="font-semibold text-ember-deep">(includes ₦{{ number_format($nextLateFee, 2) }} late fee)</span> @endif
                                </p>
                            </form>
                            @endif

                            <form action="{{ route('orders.pay.partial', $order) }}" method="POST" class="rounded-xl border border-ink/10 bg-paper-deep/40 p-4">
                                @csrf
                                <label for="os-partial-amount" class="os-label">Pay any amount</label>
                                <div class="flex gap-2">
                                    <input id="os-partial-amount" type="number" name="amount" min="100" max="{{ (float) $order->remaining_amount }}" step="0.01" placeholder="₦0.00" class="os-input" required>
                                    <button type="submit" class="os-btn os-btn-brand os-btn-sm shrink-0" aria-label="Pay partial amount"><i class="bi bi-arrow-right"></i></button>
                                </div>
                            </form>

                            <form action="{{ route('orders.pay.wallet', $order) }}" method="POST">
                                @csrf
                                <input type="hidden" name="amount" value="{{ $nextDue > 0 ? $nextDue : (float) $order->remaining_amount }}">
                                <button type="submit" class="os-btn w-full py-3" style="background:rgba(47,158,68,0.1);color:var(--grass-deep);border:1.5px solid rgba(47,158,68,0.3);">
                                    <i class="bi bi-wallet2"></i> Pay with wallet
                                    <span class="text-xs opacity-75">(₦{{ number_format($walletBalance, 0) }} available)</span>
                                </button>
                            </form>

                            <form action="{{ route('orders.pay.full', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="os-btn os-btn-ghost w-full py-3"><i class="bi bi-check-all"></i> Pay full balance</button>
                            </form>
                        @else
                            <div class="py-4 text-center">
                                <i class="bi bi-check-circle-fill text-4xl text-grass-deep"></i>
                                <p class="mt-3 text-sm font-bold text-ink">Fully paid — it's all yours!</p>
                                <p class="mt-1 text-xs text-slate">Nothing left to pay on this order.</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-5 space-y-2 border-t border-ink/10 pt-5">
                        @if(in_array($order->status, ['pending', 'processing', 'partial_paid']) && $order->installmentPlan)
                        <a href="{{ route('orders.change.plan.form', $order) }}" class="os-btn os-btn-ghost w-full"><i class="bi bi-arrow-repeat"></i> Change plan</a>
                        <a href="{{ route('orders.exchange.form', $order) }}" class="os-btn os-btn-ghost w-full"><i class="bi bi-arrow-left-right"></i> Exchange product</a>
                        @endif
                        <button type="button" class="os-btn os-btn-danger w-full" @click="open = true"><i class="bi bi-x-circle"></i> Cancel order</button>
                    </div>
                </div>

                {{-- Cancel confirmation (Alpine modal) --}}
                <div x-cloak>
                    <form action="{{ route('orders.cancel', $order) }}" method="POST">
                        @csrf
                        <input type="hidden" name="reason" :value="reason">
                        <input type="hidden" name="accept_fee" value="1">
                        <div x-show="open" x-transition.opacity class="fixed inset-0 z-[90] flex items-center justify-center bg-ink/60 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-label="Cancel order" @keydown.escape.window="open = false">
                            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-lift" @click.outside="open = false">
                                <div class="flex items-start gap-3">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-ember/10 text-lg text-ember-deep"><i class="bi bi-x-circle"></i></span>
                                    <div>
                                        <h3 class="font-display text-lg font-bold text-ink">Cancel this order?</h3>
                                        <p class="mt-1 text-sm text-slate">100% of what you've paid is refunded to your wallet as store credit. This can't be undone.</p>
                                    </div>
                                </div>
                                <div class="mt-5">
                                    <label for="os-cancel-reason" class="os-label">Reason (optional)</label>
                                    <textarea id="os-cancel-reason" x-model="reason" rows="3" class="os-input" placeholder="Why are you cancelling?"></textarea>
                                </div>
                                <div class="mt-5 flex flex-wrap justify-end gap-3">
                                    <button type="button" class="os-btn os-btn-ghost" @click="open = false">Keep order</button>
                                    <button type="submit" class="os-btn os-btn-danger"><i class="bi bi-check-lg"></i> Yes, cancel order</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Star ratings for the post-delivery review prompt
    document.querySelectorAll('.fp-star-input').forEach(group => {
        const targetId = group.dataset.target;
        const input = document.getElementById(targetId);
        const stars = group.querySelectorAll('i[data-star]');
        const paint = (value) => {
            stars.forEach(s => {
                const on = parseInt(s.dataset.star) <= value;
                s.classList.toggle('bi-star-fill', on);
                s.classList.toggle('bi-star', !on);
                s.style.color = on ? 'var(--mango)' : 'rgba(26,27,35,0.2)';
            });
        };
        stars.forEach(star => {
            star.addEventListener('mouseenter', () => paint(parseInt(star.dataset.star)));
            star.addEventListener('click', () => { input.value = star.dataset.star; paint(parseInt(star.dataset.star)); });
            star.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    input.value = star.dataset.star;
                    paint(parseInt(star.dataset.star));
                }
            });
        });
        group.addEventListener('mouseleave', () => paint(parseInt(input.value || 5)));
    });
});
</script>
@endpush
