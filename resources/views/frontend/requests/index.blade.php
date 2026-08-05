@extends('frontend.layouts.store')
@section('title', 'My Requests — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-inboxes-fill"></i> My requests</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Requests &amp; reviews</h1>
        <p class="mt-2 text-sm text-slate sm:text-base">Track your plan changes, exchanges, product requests and account closure.</p>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section">
    <div class="mx-auto max-w-5xl px-4 sm:px-6">

        @php
            $pendingCount = $planChanges->where('status', 'pending')->count()
                + $exchanges->where('status', 'pending')->count()
                + $productRequests->where('status', 'submitted')->count()
                + $productRequests->where('status', 'under_review')->count()
                + (($deletionRequest && $deletionRequest->status === 'pending') ? 1 : 0);
        @endphp
        <div class="grid grid-cols-3 gap-4" x-reveal>
            <div class="os-card os-card-hover p-5 text-center">
                <p class="font-mono text-2xl font-bold text-mango-ink">{{ $pendingCount }}</p>
                <p class="text-xs text-slate">Pending review</p>
            </div>
            <div class="os-card os-card-hover p-5 text-center">
                <p class="font-mono text-2xl font-bold text-grass-deep">{{ $planChanges->where('status', 'approved')->count() + $exchanges->where('status', 'approved')->count() + $productRequests->where('status', 'approved')->count() }}</p>
                <p class="text-xs text-slate">Approved</p>
            </div>
            <div class="os-card os-card-hover p-5 text-center">
                <p class="font-mono text-2xl font-bold text-ember-deep">{{ $planChanges->where('status', 'rejected')->count() + $exchanges->where('status', 'rejected')->count() + $productRequests->where('status', 'rejected')->count() }}</p>
                <p class="text-xs text-slate">Rejected</p>
            </div>
        </div>

        {{-- ===== PLAN CHANGE REQUESTS ===== --}}
        <div class="os-card mt-8 overflow-hidden" x-reveal="80">
            <h2 class="flex items-center gap-2 border-b border-ink/10 px-5 py-4 font-display text-sm font-bold text-ink"><i class="bi bi-arrow-repeat text-mango-deep"></i> Plan change requests</h2>
            @forelse($planChanges as $r)
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-ink/5 px-5 py-4 transition-colors last:border-b-0 hover:bg-paper-deep/40">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-ink">
                        {{ $r->currentPlan?->name ?? 'Current' }} <i class="bi bi-arrow-right mx-1 text-mango-deep"></i> {{ $r->requestedPlan?->name ?? 'New plan' }}
                    </p>
                    <p class="mt-0.5 text-xs text-slate">Order #{{ $r->order_id }} · {{ $r->created_at->format('M d, Y') }}</p>
                    @if($r->reason)<p class="mt-1 text-sm italic text-slate">"{{ Str::limit($r->reason, 120) }}"</p>@endif
                    @if($r->admin_notes)
                    <p class="mt-2 inline-flex items-center gap-1.5 rounded-lg border border-brand/20 bg-indigo/5 px-2.5 py-1 text-xs text-brand"><i class="bi bi-chat-left-text-fill"></i> {{ $r->admin_notes }}</p>
                    @endif
                </div>
                @php
                    $chip = $r->status === 'approved' ? 'os-chip-grass' : ($r->status === 'rejected' ? 'os-chip-ember' : 'os-chip-mango');
                    $icon = $r->status === 'approved' ? 'bi-check-circle-fill' : ($r->status === 'rejected' ? 'bi-x-circle-fill' : 'bi-clock-fill');
                @endphp
                <span class="os-chip {{ $chip }}"><i class="bi {{ $icon }}"></i> {{ ucfirst($r->status) }}</span>
            </div>
            @empty
            <div class="flex flex-col items-center px-5 py-10 text-center">
                <span class="os-empty-icon"><i class="bi bi-arrow-repeat"></i></span>
                <p class="mt-4 max-w-sm text-sm text-slate">No plan change requests yet. You can request one from any active order.</p>
                <a href="{{ route('orders.index') }}" class="os-btn os-btn-ghost os-btn-sm mt-5"><i class="bi bi-bag-check-fill"></i> View your orders</a>
            </div>
            @endforelse
        </div>

        {{-- ===== EXCHANGE REQUESTS ===== --}}
        <div class="os-card mt-6 overflow-hidden" x-reveal="120">
            <h2 class="flex items-center gap-2 border-b border-ink/10 px-5 py-4 font-display text-sm font-bold text-ink"><i class="bi bi-arrow-left-right text-mango-deep"></i> Exchange requests</h2>
            @forelse($exchanges as $r)
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-ink/5 px-5 py-4 transition-colors last:border-b-0 hover:bg-paper-deep/40">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-ink">
                        {{ Str::limit($r->currentProduct?->name ?? 'Ordered product', 35) }} <i class="bi bi-arrow-right mx-1 text-mango-deep"></i> {{ Str::limit($r->requestedProduct?->name ?? 'Wishlist item', 35) }}
                    </p>
                    <p class="mt-0.5 text-xs text-slate">Order #{{ $r->order_id }} · {{ $r->created_at->format('M d, Y') }}</p>
                    @if($r->reason)<p class="mt-1 text-sm italic text-slate">"{{ Str::limit($r->reason, 120) }}"</p>@endif
                    @if($r->admin_notes)
                    <p class="mt-2 inline-flex items-center gap-1.5 rounded-lg border border-brand/20 bg-indigo/5 px-2.5 py-1 text-xs text-brand"><i class="bi bi-chat-left-text-fill"></i> {{ $r->admin_notes }}</p>
                    @endif
                </div>
                @php
                    $chip = in_array($r->status, ['approved', 'completed']) ? 'os-chip-grass' : ($r->status === 'rejected' ? 'os-chip-ember' : 'os-chip-mango');
                    $icon = in_array($r->status, ['approved', 'completed']) ? 'bi-check-circle-fill' : ($r->status === 'rejected' ? 'bi-x-circle-fill' : 'bi-clock-fill');
                @endphp
                <span class="os-chip {{ $chip }}"><i class="bi {{ $icon }}"></i> {{ ucfirst($r->status) }}</span>
            </div>
            @empty
            <div class="flex flex-col items-center px-5 py-10 text-center">
                <span class="os-empty-icon"><i class="bi bi-arrow-left-right"></i></span>
                <p class="mt-4 max-w-sm text-sm text-slate">No exchange requests yet. You can request one from any active order.</p>
                <a href="{{ route('orders.index') }}" class="os-btn os-btn-ghost os-btn-sm mt-5"><i class="bi bi-bag-check-fill"></i> View your orders</a>
            </div>
            @endforelse
        </div>

        {{-- ===== PRODUCT REQUESTS ===== --}}
        <div class="os-card mt-6 overflow-hidden" x-reveal="160">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink/10 px-5 py-4">
                <h2 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-plus-square-fill text-mango-deep"></i> Product requests</h2>
                <a href="{{ route('requests.product.create') }}" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-plus-lg"></i> Request a product</a>
            </div>
            @forelse($productRequests as $r)
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-ink/5 px-5 py-4 transition-colors last:border-b-0 hover:bg-paper-deep/40">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-ink">{{ $r->product_name }}</p>
                    <p class="mt-0.5 text-xs text-slate">
                        {{ $r->created_at->format('M d, Y') }}
                        @if($r->product_url) · <a href="{{ $r->product_url }}" target="_blank" rel="noopener" class="font-semibold text-brand hover:underline">View link <i class="bi bi-box-arrow-up-right"></i></a>@endif
                    </p>
                    @if($r->reason)<p class="mt-1 text-sm italic text-slate">"{{ Str::limit($r->reason, 120) }}"</p>@endif
                    @if($r->admin_notes)
                    <p class="mt-2 inline-flex items-center gap-1.5 rounded-lg border border-brand/20 bg-indigo/5 px-2.5 py-1 text-xs text-brand"><i class="bi bi-chat-left-text-fill"></i> {{ $r->admin_notes }}</p>
                    @endif
                </div>
                @php
                    $chip = in_array($r->status, ['approved', 'completed']) ? 'os-chip-grass' : ($r->status === 'rejected' ? 'os-chip-ember' : 'os-chip-mango');
                    $icon = in_array($r->status, ['approved', 'completed']) ? 'bi-check-circle-fill' : ($r->status === 'rejected' ? 'bi-x-circle-fill' : 'bi-clock-fill');
                @endphp
                <span class="os-chip {{ $chip }}"><i class="bi {{ $icon }}"></i> {{ ucfirst(str_replace('_', ' ', $r->status)) }}</span>
            </div>
            @empty
            <div class="flex flex-col items-center px-5 py-10 text-center">
                <span class="os-empty-icon"><i class="bi bi-plus-square"></i></span>
                <p class="mt-4 max-w-sm text-sm text-slate">No product requests yet. Tell us what you'd love us to stock.</p>
                <a href="{{ route('requests.product.create') }}" class="os-btn os-btn-brand os-btn-sm mt-5"><i class="bi bi-plus-lg"></i> Request a product</a>
            </div>
            @endforelse
        </div>

        {{-- ===== ACCOUNT CLOSURE ===== --}}
        @if($deletionRequest)
        <div class="os-card mt-6 overflow-hidden border-ember/20" x-reveal="200">
            <h2 class="flex items-center gap-2 border-b border-ink/10 px-5 py-4 font-display text-sm font-bold text-ink"><i class="bi bi-person-x text-ember-deep"></i> Account closure</h2>
            <div class="flex flex-wrap items-center justify-between gap-4 px-5 py-4">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-ink">Account closure request</p>
                    <p class="mt-0.5 text-xs text-slate">Submitted {{ $deletionRequest->created_at->format('M d, Y') }}</p>
                    @if($deletionRequest->reason)<p class="mt-1 text-sm italic text-slate">"{{ Str::limit($deletionRequest->reason, 120) }}"</p>@endif
                    @if($deletionRequest->admin_notes)
                    <p class="mt-2 inline-flex items-center gap-1.5 rounded-lg border border-brand/20 bg-indigo/5 px-2.5 py-1 text-xs text-brand"><i class="bi bi-chat-left-text-fill"></i> {{ $deletionRequest->admin_notes }}</p>
                    @endif
                </div>
                @php
                    $chip = $deletionRequest->status === 'approved' ? 'os-chip-ember' : ($deletionRequest->status === 'rejected' ? 'os-chip-grass' : 'os-chip-mango');
                    $icon = $deletionRequest->status === 'approved' ? 'bi-check-circle-fill' : ($deletionRequest->status === 'rejected' ? 'bi-x-circle-fill' : 'bi-clock-fill');
                @endphp
                <span class="os-chip {{ $chip }}"><i class="bi {{ $icon }}"></i> {{ ucfirst($deletionRequest->status) }}</span>
            </div>
        </div>
        @endif
    </div>
</section>

@endsection
