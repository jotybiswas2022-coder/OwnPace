@extends('frontend.layouts.store')
@section('title', 'My Orders — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-box-seam-fill"></i> My orders</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Order history</h1>
        <p class="mt-2 text-sm text-slate sm:text-base">Track your plans, continue payments, and manage everything you own.</p>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">

        @php
            $tabs = [
                'all' => ['label' => 'All', 'icon' => 'bi-grid-fill'],
                'active' => ['label' => 'Active plans', 'icon' => 'bi-arrow-repeat'],
                'completed' => ['label' => 'Completed', 'icon' => 'bi-check-circle-fill'],
                'cancelled' => ['label' => 'Cancelled', 'icon' => 'bi-x-circle-fill'],
            ];
            $activeOrderStatuses = ['pending', 'processing', 'partial_paid', 'shipped'];
        @endphp

        <nav class="os-tabs" aria-label="Filter orders">
            @foreach($tabs as $key => $t)
            <a href="{{ route('orders.index', ['tab' => $key]) }}" class="os-tab {{ $tab === $key ? 'os-tab-active' : '' }}" @if($tab === $key) aria-current="page" @endif>
                <i class="bi {{ $t['icon'] }}"></i> {{ $t['label'] }}
                <span class="font-mono text-xs opacity-70">{{ $counts[$key] }}</span>
            </a>
            @endforeach
        </nav>

        <div class="mt-8 space-y-5">
            @forelse($orders as $index => $order)
            @php
                $badge = orderProgressBadge($order);
                $pct = (float) $order->grand_total > 0 ? round(((float) $order->paid_amount / (float) $order->grand_total) * 100) : 0;
                $isActive = in_array($order->status, $activeOrderStatuses);
            @endphp
            <div class="os-card os-card-hover overflow-hidden" x-reveal="{{ min($index * 60, 240) }}">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/10 bg-paper-deep/40 px-5 py-4">
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                        <p class="flex items-center gap-2 text-sm font-bold text-ink"><i class="bi bi-receipt text-mango-deep"></i> Order #{{ $order->id }}</p>
                        <p class="flex items-center gap-1.5 text-xs text-slate"><i class="bi bi-calendar3"></i> {{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="os-chip {{ str_contains($badge['class'] ?? '', 'completed') ? 'os-chip-grass' : (str_contains($badge['class'] ?? '', 'cancelled') ? 'os-chip-ember' : 'os-chip-mango') }}" aria-label="Progress: {{ $badge['label'] }}">
                            <i class="bi {{ $badge['icon'] }}"></i> {{ $badge['label'] }}
                        </span>
                        <span class="os-price text-base">{{ formatPrice($order->grand_total, 0) }}</span>
                    </div>
                </div>

                <div class="px-5 py-4">
                    @foreach($order->items->take(3) as $item)
                    <div class="flex items-center gap-3 rounded-lg px-2 py-1.5 transition-colors hover:bg-paper-deep/50">
                        @if($item->product && $item->product->primaryImage)
                            <img src="{{ asset('storage/'.$item->product->primaryImage->image_path) }}" alt="{{ $item->product->name }}" loading="lazy" decoding="async" class="h-11 w-11 shrink-0 rounded-lg object-cover ring-1 ring-ink/10">
                        @else
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-paper-deep text-ink/20"><i class="bi bi-image"></i></span>
                        @endif
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-ink">{{ $item->product?->name ?? 'Product' }}</p>
                            <p class="text-xs text-slate">Qty: {{ $item->quantity }} × {{ formatPrice($item->price, 0) }}</p>
                        </div>
                    </div>
                    @endforeach
                    @if($order->items->count() > 3)
                    <p class="pl-2 pt-1 text-xs font-medium text-slate">+{{ $order->items->count() - 3 }} more items</p>
                    @endif
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4 border-t border-ink/10 bg-paper-deep/40 px-5 py-4">
                    <div class="flex min-w-0 flex-1 items-center gap-3">
                        <span class="shrink-0 text-xs font-semibold text-slate">{{ $pct }}% paid</span>
                        <div class="h-1.5 max-w-[160px] flex-1 overflow-hidden rounded-full bg-ink/10" role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $pct }}% paid">
                            <div class="h-full rounded-full {{ $pct >= 100 ? 'bg-grass' : 'bg-mango' }}" style="width:{{ min($pct, 100) }}%"></div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('orders.show', $order) }}" class="os-btn os-btn-ghost os-btn-sm" aria-label="View details for order #{{ $order->id }}"><i class="bi bi-eye"></i> View details</a>
                        @if($isActive)
                        <a href="{{ route('payment.gateway', $order) }}" class="os-btn os-btn-mango os-btn-sm" aria-label="Continue paying for order #{{ $order->id }}"><i class="bi bi-credit-card"></i> Continue payment</a>
                        @endif
                        @if($isActive && $order->installmentPlan)
                        <a href="{{ route('orders.change.plan.form', $order) }}" class="os-btn os-btn-ghost os-btn-sm" aria-label="Change plan for order #{{ $order->id }}"><i class="bi bi-arrow-repeat"></i> Change plan</a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="mx-auto max-w-lg" x-reveal>
                <x-frontend.partials.empty-state
                    icon="{{ $tab === 'cancelled' ? 'bi-x-circle' : ($tab === 'completed' ? 'bi-check2-circle' : 'bi-inbox') }}"
                    :title="$tab === 'cancelled' ? 'No cancelled orders' : ($tab === 'completed' ? 'No completed orders' : 'No orders yet')"
                    :message="$tab === 'all' || $tab === 'active'
                        ? 'You don\'t have any plans right now. Start shopping and your orders will appear here — pay them down at your own pace.'
                        : 'Nothing here yet — your activity in this section will show up as you use your account.'"
                    actionLabel="Browse products"
                    actionUrl="{{ url('/shop') }}"
                />
            </div>
            @endforelse
        </div>

        @if(method_exists($orders, 'links') && $orders->hasPages())
        <div class="mt-10">{{ $orders->links() }}</div>
        @endif

        {{-- ===== SAVED PAYMENT METHODS (tokenized — no raw card numbers) ===== --}}
        <div class="os-card mt-12 overflow-hidden" x-reveal>
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/10 px-5 py-4">
                <h2 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-credit-card-2-front-fill text-mango-deep"></i> Saved payment methods</h2>
                <a href="{{ route('profile.cards') }}" class="text-xs font-semibold text-brand transition-colors hover:text-brand-deep"><i class="bi bi-gear-fill"></i> Manage</a>
            </div>
            @if(($savedCards ?? collect())->count() > 0 || ($bankAccounts ?? collect())->count() > 0)
            <div class="grid gap-3 p-5 sm:grid-cols-2">
                @foreach($savedCards ?? [] as $card)
                <div class="flex items-center gap-3 rounded-xl border border-ink/10 bg-paper-deep/40 p-4 transition-colors hover:border-mango/40">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-mango/15 text-lg text-mango-deep"><i class="bi bi-credit-card-fill"></i></span>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-ink">{{ $card->card_brand ?? 'Card' }} •••• {{ $card->card_number_last4 }}</p>
                        <p class="text-xs text-slate">Expires {{ $card->expiry_month }}/{{ $card->expiry_year }}</p>
                    </div>
                </div>
                @endforeach
                @foreach($bankAccounts ?? [] as $bank)
                <div class="flex items-center gap-3 rounded-xl border border-ink/10 bg-paper-deep/40 p-4 transition-colors hover:border-mango/40">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand/10 text-lg text-brand"><i class="bi bi-bank2"></i></span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-ink">{{ $bank->bank_name }} •••• {{ substr($bank->account_number, -4) }}</p>
                        <p class="truncate text-xs text-slate">{{ $bank->account_name }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <p class="flex items-center gap-2 border-t border-ink/5 px-5 py-3 text-xs text-slate">
                <i class="bi bi-shield-lock-fill text-grass-deep"></i>
                Cards are tokenized by our payment provider — we never see or store your full card number.
            </p>
            @else
            <div class="px-5 py-10 text-center">
                <i class="bi bi-credit-card-2-front text-3xl text-ink/15"></i>
                <p class="mt-3 text-sm text-slate">No saved payment methods yet. Your cards and bank accounts will appear here after your first payment.</p>
                <a href="{{ route('profile.banks') }}" class="os-btn os-btn-ghost os-btn-sm mt-4"><i class="bi bi-bank"></i> Add bank account</a>
            </div>
            @endif
        </div>
    </div>
</section>

@endsection
