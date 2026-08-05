@extends('frontend.layouts.store')
@section('title', 'My Profile — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-person-circle"></i> My profile</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Welcome, {{ auth()->user()->name ?? auth()->user()->email }}</h1>
        <p class="mt-2 text-sm text-slate sm:text-base">Manage your account settings and view your activity.</p>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">

        {{-- Stats --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3" x-reveal>
            <div class="os-card os-card-hover flex items-center gap-4 p-5">
                <i class="bi bi-box-seam-fill text-2xl text-mango-deep"></i>
                <div>
                    <p class="font-mono text-lg font-bold text-ink">{{ auth()->user()->orders()->count() }}</p>
                    <p class="text-xs text-slate">Orders</p>
                </div>
            </div>
            <div class="os-card os-card-hover flex items-center gap-4 p-5">
                <i class="bi bi-coin text-2xl text-mango-deep"></i>
                <div>
                    <p class="font-mono text-lg font-bold text-ink">{{ auth()->user()->installmentPayments()->count() }}</p>
                    <p class="text-xs text-slate">Installments</p>
                </div>
            </div>
            <div class="os-card os-card-hover col-span-2 flex items-center gap-4 p-5 sm:col-span-1">
                <i class="bi bi-wallet2 text-2xl text-brand"></i>
                <div>
                    <p class="os-price text-lg font-bold">{{ formatPrice(auth()->user()->wallet?->balance ?? 0, 0) }}</p>
                    <p class="text-xs text-slate">Wallet balance</p>
                </div>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">

            {{-- Personal info --}}
            <div class="os-card overflow-hidden" x-reveal="80">
                <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
                    <h2 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-person-fill text-mango-deep"></i> Personal information</h2>
                    <a href="{{ route('profile.edit') }}" class="text-xs font-semibold text-brand transition-colors hover:text-brand-deep"><i class="bi bi-pencil-fill"></i> Edit</a>
                </div>
                <dl class="grid gap-4 p-5 sm:grid-cols-2">
                    <div>
                        <dt class="os-label mb-1">Full name</dt>
                        <dd class="text-sm font-medium text-ink">{{ auth()->user()->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="os-label mb-1">Email address</dt>
                        <dd class="break-all text-sm font-medium text-ink">{{ auth()->user()->email }}</dd>
                    </div>
                    <div>
                        <dt class="os-label mb-1">Phone number</dt>
                        <dd class="text-sm font-medium text-ink">{{ auth()->user()->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="os-label mb-1">Member since</dt>
                        <dd class="text-sm font-medium text-ink">{{ auth()->user()->created_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Verification status --}}
            <div class="os-card overflow-hidden" x-reveal="120">
                <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
                    <h2 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-patch-check-fill text-mango-deep"></i> Verification status</h2>
                    <a href="{{ route('profile.verification') }}" class="text-xs font-semibold text-brand transition-colors hover:text-brand-deep">Manage</a>
                </div>
                @php
                    $vTypes = [
                        ['key' => 'identity_card', 'icon' => 'bi-person-badge-fill', 'label' => 'Identity card'],
                        ['key' => 'payment_card', 'icon' => 'bi-credit-card-2-front-fill', 'label' => 'Payment card'],
                        ['key' => 'bank_account', 'icon' => 'bi-bank2', 'label' => 'Bank account'],
                        ['key' => 'email', 'icon' => 'bi-envelope-fill', 'label' => 'Email address'],
                        ['key' => 'store_terms', 'icon' => 'bi-file-earmark-text-fill', 'label' => 'Store terms'],
                        ['key' => 'delivery_address', 'icon' => 'bi-geo-alt-fill', 'label' => 'Delivery address'],
                    ];
                @endphp
                <div class="grid grid-cols-2 gap-3 p-5 sm:grid-cols-3">
                    @foreach($vTypes as $vt)
                    @php
                        $st = $verificationStatuses[$vt['key']] ?? 'unsubmitted';
                        $chip = [
                            'approved' => ['os-chip-grass', 'bi-check-circle-fill', 'Verified'],
                            'pending' => ['os-chip-mango', 'bi-clock-fill', 'Pending'],
                            'rejected' => ['os-chip-ember', 'bi-x-circle-fill', 'Rejected'],
                            'unsubmitted' => ['os-chip-slate', 'bi-dash-circle-fill', 'Not submitted'],
                        ][$st] ?? ['os-chip-slate', 'bi-dash-circle-fill', 'Not submitted'];
                    @endphp
                    <div class="rounded-xl border border-ink/10 bg-paper-deep/40 p-3.5">
                        <i class="bi {{ $vt['icon'] }} text-lg text-brand"></i>
                        <p class="mt-2 text-xs font-semibold text-ink">{{ $vt['label'] }}</p>
                        <span class="os-chip mt-1.5 px-2 py-0.5 text-[10px] {{ $chip[0] }}"><i class="bi {{ $chip[1] }}"></i> {{ $chip[2] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent orders --}}
            <div class="os-card overflow-hidden" x-reveal="160">
                <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
                    <h2 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-box-seam-fill text-mango-deep"></i> Recent orders</h2>
                    <a href="{{ route('orders.index') }}" class="text-xs font-semibold text-brand transition-colors hover:text-brand-deep">View all</a>
                </div>
                <div class="divide-y divide-ink/5">
                    @php $recentOrders = auth()->user()->orders()->latest()->take(5)->get(); @endphp
                    @forelse($recentOrders as $order)
                    @php $badge = orderProgressBadge($order); @endphp
                    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3.5 transition-colors hover:bg-paper-deep/40">
                        <div class="flex items-center gap-3">
                            <span class="os-chip {{ str_contains($badge['class'] ?? '', 'completed') ? 'os-chip-grass' : (str_contains($badge['class'] ?? '', 'cancelled') ? 'os-chip-ember' : 'os-chip-mango') }}"><i class="bi {{ $badge['icon'] }}"></i> {{ $badge['label'] }}</span>
                            <div>
                                <p class="text-sm font-bold text-ink">Order #{{ $order->id }}</p>
                                <p class="text-xs text-slate">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="os-price">{{ formatPrice($order->grand_total, 0) }}</span>
                            <a href="{{ route('orders.show', $order) }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-eye"></i> View</a>
                        </div>
                    </div>
                    @empty
                    <div class="px-5 py-10 text-center">
                        <i class="bi bi-inbox text-3xl text-ink/15"></i>
                        <p class="mt-3 text-sm text-slate">No orders yet. <a href="{{ url('/shop') }}" class="font-semibold text-brand underline underline-offset-2">Start shopping!</a></p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Account closure --}}
            <div class="os-card overflow-hidden border-ember/20" x-reveal="200">
                <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
                    <h2 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-person-x text-ember-deep"></i> Account closure</h2>
                </div>
                <div class="p-5">
                    @if(isset($deletionRequest) && $deletionRequest->status === 'pending')
                        <div class="flex items-center gap-2 rounded-xl border border-mango/30 bg-mango/10 p-3.5 text-sm font-semibold text-mango-ink">
                            <i class="bi bi-hourglass-split"></i> Your account closure request is under review.
                        </div>
                        <p class="mt-3 text-sm text-slate">We'll get back to you once it's processed. You can keep using your account until then.</p>
                    @elseif(isset($deletionRequest) && $deletionRequest->status === 'approved')
                        <div class="flex items-center gap-2 rounded-xl border border-ember/30 bg-ember/5 p-3.5 text-sm font-semibold text-ember-deep">
                            <i class="bi bi-check-circle-fill"></i> Your account closure request was approved.
                        </div>
                        @if($deletionRequest->admin_notes)
                        <p class="mt-3 text-sm text-slate">Note: {{ $deletionRequest->admin_notes }}</p>
                        @endif
                    @else
                        <p class="text-sm leading-relaxed text-slate">
                            Closing your account is permanent and can't be undone. Any remaining wallet balance and active plans must be settled first. This request is reviewed by our team — it's not processed automatically.
                        </p>
                        @if(auth()->user()->activeOrders()->count() > 0)
                            <div class="mt-4 flex items-start gap-2 rounded-xl border border-brand/20 bg-indigo/5 p-3.5 text-sm text-ink">
                                <i class="bi bi-info-circle-fill mt-0.5 text-brand"></i> You have active orders. Finish them before requesting closure.
                            </div>
                        @else
                            <form method="POST" action="{{ route('profile.deletion.request') }}" class="mt-4">
                                @csrf
                                <label for="os-close-reason" class="os-label">Reason (optional)</label>
                                <textarea id="os-close-reason" name="reason" rows="3" class="os-input" placeholder="Tell us why you're leaving"></textarea>
                                <button type="submit" class="os-btn os-btn-danger mt-4"><i class="bi bi-person-x"></i> Request account closure</button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
