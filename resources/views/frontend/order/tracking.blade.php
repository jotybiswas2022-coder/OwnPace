@extends('frontend.layouts.store')
@section('title', 'Track Order #'.$order->id.' — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-truck-front-fill"></i> Delivery tracking</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Track Order #{{ $order->id }}</h1>
        <p class="mt-2 text-sm text-slate">Follow your order from processing to delivery</p>
    </div>
</section>

<section class="os-section">
    <div class="mx-auto max-w-4xl px-4 sm:px-6">
        <div class="os-card overflow-hidden" x-reveal>
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-ink/10 bg-paper-deep/40 px-6 py-5">
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-mango/15 text-xl text-mango-deep"><i class="bi bi-truck-front-fill"></i></span>
                    <div>
                        <h2 class="font-display text-base font-bold text-ink">Delivery status</h2>
                        @php $dStatus = $order->delivery_status ?? 'pending'; @endphp
                        <span class="os-chip mt-1 {{ $dStatus === 'delivered' ? 'os-chip-grass' : ($dStatus === 'pending' ? 'os-chip-mango' : 'os-chip-brand') }}">
                            <i class="bi {{ $dStatus === 'delivered' ? 'bi-check-circle-fill' : 'bi-truck' }}"></i> {{ ucfirst(str_replace('_', ' ', $dStatus)) }}
                        </span>
                    </div>
                </div>
                <span class="os-price text-2xl">₦{{ number_format($order->total, 0) }}</span>
            </div>

            {{-- Timeline --}}
            <div class="p-6 sm:p-8">
                @php
                    $statuses = [
                        ['key' => 'pending', 'icon' => 'bi-clock-fill', 'label' => 'Order placed', 'time' => $order->created_at],
                        ['key' => 'processing', 'icon' => 'bi-gear-fill', 'label' => 'Processing', 'time' => $order->updated_at],
                        ['key' => 'shipped', 'icon' => 'bi-truck-front-fill', 'label' => 'Shipped', 'time' => null],
                        ['key' => 'delivered', 'icon' => 'bi-check-circle-fill', 'label' => 'Delivered', 'time' => null],
                    ];
                    $currentStatus = $order->delivery_status ?? 'pending';
                    $found = false;
                @endphp
                <ol class="space-y-0">
                    @foreach($statuses as $st)
                        @php
                            $isComplete = !$found;
                            if ($st['key'] == $currentStatus) $found = true;
                            $isActive = $st['key'] == $currentStatus;
                        @endphp
                        <li class="relative flex gap-4 pb-8 last:pb-0 {{ $isComplete && !$isActive ? '' : '' }}">
                            @if(!$loop->last)
                            <span class="absolute left-5 top-12 bottom-0 w-0.5 {{ ($isComplete && !$isActive) || $found ? 'bg-grass' : 'bg-ink/10' }}" aria-hidden="true"></span>
                            @endif
                            <span class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-base
                                {{ $isActive ? 'bg-mango text-ink shadow-[0_0_0_6px_rgba(245,166,35,0.18)]' : ($isComplete ? 'bg-grass text-white' : 'bg-paper-deep text-slate ring-1 ring-ink/15') }}">
                                <i class="bi {{ $st['icon'] }}"></i>
                            </span>
                            <div class="pt-1.5">
                                <p class="text-sm font-bold text-ink">{{ $st['label'] }}</p>
                                @if($st['time'])
                                    <p class="text-xs text-slate">{{ $st['time']->format('M d, Y h:i A') }}</p>
                                @elseif($isActive)
                                    <p class="text-xs font-semibold text-mango-ink">In progress…</p>
                                @else
                                    <p class="text-xs text-slate">Pending</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>

                {{-- Tracking updates --}}
                @if($order->deliveryTrackings && $order->deliveryTrackings->count() > 0)
                <div class="mt-8 border-t border-ink/5 pt-6">
                    <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-clock-history text-mango-deep"></i> Tracking updates</h3>
                    <div class="mt-4 space-y-3">
                        @foreach($order->deliveryTrackings as $dt)
                        <div class="flex items-start gap-3">
                            <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ in_array($dt->status, ['delivered', 'eligible']) ? 'bg-grass' : ($dt->status === 'shipped' ? 'bg-brand' : 'bg-ink/15') }}" aria-hidden="true"></span>
                            <div>
                                <p class="text-sm font-semibold text-ink">{{ $dt->description }}</p>
                                <p class="text-xs text-slate">{{ $dt->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-ink/10 bg-paper-deep/40 px-6 py-4">
                <p class="text-xs text-slate"><i class="bi bi-info-circle-fill text-mango-deep"></i> Once 70% is paid, your item becomes eligible for shipping.</p>
                <a href="{{ route('orders.show', $order) }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-eye"></i> View order details</a>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('orders.index') }}" class="text-sm font-semibold text-brand transition-colors hover:text-brand-deep"><i class="bi bi-arrow-left"></i> Back to my orders</a>
        </div>
    </div>
</section>

@endsection
