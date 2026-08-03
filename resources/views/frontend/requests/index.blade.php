@extends('frontend.app')
@section('title', 'My Requests — OwnPace Store')

@push('styles')
<style>
.fp-rq-hero {
    position: relative; padding: 50px 0 28px; overflow: hidden;
    background: linear-gradient(135deg, #0A0A0B 0%, #1A1A1E 30%, #0A0A0B 70%);
    border-bottom: 1px solid var(--card-border);
}
.fp-rq-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(234,179,8,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(234,179,8,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}
.fp-rq-orb {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -150px; right: -80px; pointer-events: none;
    animation: rqPulse 6s ease-in-out infinite;
}
@keyframes rqPulse { 0%,100%{transform:scale(1);opacity:0.4} 50%{transform:scale(1.15);opacity:0.8} }

.fp-rq-section { padding-bottom: 80px; min-height: 60vh; }
.fp-alert { display:flex;align-items:center;gap:10px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);color:#4ade80;padding:14px 20px;border-radius:var(--radius-sm);font-weight:500;font-size:13px;margin-bottom:24px;animation:alertSlide 0.4s ease-out; }
.fp-alert.error { background:rgba(239,68,68,0.08);border-color:rgba(239,68,68,0.25);color:#f87171; }
@keyframes alertSlide { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }

.fp-rq-card { background:var(--card-dark);border:1px solid var(--card-border);border-radius:var(--radius);overflow:hidden;margin-bottom:20px;transition:all 0.3s; }
.fp-rq-card:hover { border-color:rgba(234,179,8,0.15); }
.fp-rq-header { display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--card-border);background:var(--surface-dark);flex-wrap:wrap;gap:10px; }
.fp-rq-header h4 { font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:8px;margin:0; }
.fp-rq-header h4 i { color:var(--gold-500); }
.fp-rq-header .fp-rq-action { font-size:12px;color:var(--gold-400);font-weight:600;display:inline-flex;align-items:center;gap:4px;text-decoration:none;padding:6px 12px;border-radius:6px;transition:all 0.3s; }
.fp-rq-header .fp-rq-action:hover { background:rgba(234,179,8,0.08); }

.fp-rq-item {
    display:flex; align-items:center; justify-content:space-between;
    gap:14px; padding:16px 20px; border-bottom:1px solid var(--card-border);
    transition:background 0.2s; flex-wrap:wrap;
}
.fp-rq-item:last-child { border-bottom:none; }
.fp-rq-item:hover { background:rgba(234,179,8,0.03); }
.fp-rq-main { flex:1; min-width:220px; }
.fp-rq-title { color:var(--text-primary); font-size:14px; font-weight:600; }
.fp-rq-title .fp-rq-arrow { color:var(--gold-500); font-size:12px; padding:0 4px; }
.fp-rq-sub { color:var(--text-dim); font-size:12px; margin-top:3px; }
.fp-rq-reason { color:var(--text-muted); font-size:13px; margin-top:6px; line-height:1.5; }
.fp-rq-note {
    margin-top:8px; padding:8px 12px; border-radius:6px;
    background:rgba(59,130,246,0.08); border:1px solid rgba(59,130,246,0.2);
    color:#60a5fa; font-size:12px; display:inline-flex; align-items:center; gap:6px;
}
.fp-rq-date { color:var(--text-dim); font-size:11px; }

.fp-rq-chip {
    display:inline-flex; align-items:center; gap:5px;
    padding:5px 12px; border-radius:8px;
    font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.3px;
    white-space:nowrap;
}
.fp-rq-chip.pending, .fp-rq-chip.submitted { background:rgba(234,179,8,0.12); color:var(--gold-400); }
.fp-rq-chip.under_review { background:rgba(59,130,246,0.12); color:#60a5fa; }
.fp-rq-chip.approved, .fp-rq-chip.completed { background:rgba(34,197,94,0.12); color:#4ade80; }
.fp-rq-chip.rejected { background:rgba(239,68,68,0.12); color:#f87171; }

.fp-rq-empty { text-align:center; padding:40px 20px; }
.fp-rq-empty-icon { width:64px; height:64px; border-radius:18px; background:var(--surface-dark); border:1px solid var(--card-border); display:flex; align-items:center; justify-content:center; margin:0 auto 14px; font-size:24px; color:var(--text-dim); }
.fp-rq-empty p { color:var(--text-muted); font-size:13px; margin:0; }

.fp-rq-stats { display:grid; grid-template-columns:repeat(3, 1fr); gap:12px; margin-bottom:20px; }
.fp-rq-stat { background:var(--card-dark); border:1px solid var(--card-border); border-radius:var(--radius-sm); padding:16px; text-align:center; transition:all 0.3s; }
.fp-rq-stat:hover { border-color:rgba(234,179,8,0.25); transform:translateY(-2px); }
.fp-rq-stat strong { display:block; font-family:'Syne',sans-serif; font-size:22px; color:var(--gold-400); }
.fp-rq-stat span { color:var(--text-dim); font-size:12px; }

@media (max-width: 768px) {
    .fp-rq-hero { padding: 36px 0 20px; }
    .fp-rq-stats { grid-template-columns:repeat(3, 1fr); gap:8px; }
}
</style>
@endpush

@section('content')
<section class="fp-rq-hero">
    <div class="fp-rq-hero-grid" aria-hidden="true"></div>
    <div class="fp-rq-orb" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-inboxes-fill"></i> My Requests</div>
            <h2>Requests &amp; Reviews</h2>
            <p>Track your plan changes, exchanges, product requests and account closure</p>
        </div>
    </div>
</section>

<section class="fp-rq-section">
    <div class="container">
        @if(session('success'))
        <div class="fp-alert reveal-up"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="fp-alert error reveal-up"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-3">
                @include('frontend.partials.account-sidebar')
            </div>

            <div class="col-lg-9">
                @php
                    $pendingCount = $planChanges->where('status', 'pending')->count()
                        + $exchanges->where('status', 'pending')->count()
                        + $productRequests->where('status', 'submitted')->count()
                        + $productRequests->where('status', 'under_review')->count()
                        + (($deletionRequest && $deletionRequest->status === 'pending') ? 1 : 0);
                @endphp
                <div class="fp-rq-stats reveal-up">
                    <div class="fp-rq-stat"><strong>{{ $pendingCount }}</strong><span>Pending review</span></div>
                    <div class="fp-rq-stat"><strong>{{ $planChanges->where('status', 'approved')->count() + $exchanges->where('status', 'approved')->count() + $productRequests->where('status', 'approved')->count() }}</strong><span>Approved</span></div>
                    <div class="fp-rq-stat"><strong>{{ $planChanges->where('status', 'rejected')->count() + $exchanges->where('status', 'rejected')->count() + $productRequests->where('status', 'rejected')->count() }}</strong><span>Rejected</span></div>
                </div>

                {{-- ===== PLAN CHANGE REQUESTS ===== --}}
                <div class="fp-rq-card reveal-left">
                    <div class="fp-rq-header">
                        <h4><i class="bi bi-arrow-repeat"></i> Plan Change Requests</h4>
                    </div>
                    @forelse($planChanges as $r)
                    <div class="fp-rq-item">
                        <div class="fp-rq-main">
                            <span class="fp-rq-title">
                                {{ $r->currentPlan?->name ?? 'Current' }} <span class="fp-rq-arrow"><i class="bi bi-arrow-right"></i></span> {{ $r->requestedPlan?->name ?? 'New plan' }}
                            </span>
                            <div class="fp-rq-sub">Order #{{ $r->order_id }} · {{ $r->created_at->format('M d, Y') }}</div>
                            @if($r->reason)<div class="fp-rq-reason">"{{ Str::limit($r->reason, 120) }}"</div>@endif
                            @if($r->admin_notes)
                            <div class="fp-rq-note"><i class="bi bi-chat-left-text-fill"></i> {{ $r->admin_notes }}</div>
                            @endif
                        </div>
                        <span class="fp-rq-chip {{ $r->status }}"><i class="bi {{ $r->status === 'approved' ? 'bi-check-circle-fill' : ($r->status === 'rejected' ? 'bi-x-circle-fill' : 'bi-clock-fill') }}"></i> {{ ucfirst($r->status) }}</span>
                    </div>
                    @empty
                    <div class="fp-rq-empty">
                        <div class="fp-rq-empty-icon"><i class="bi bi-arrow-repeat"></i></div>
                        <p>No plan change requests yet. You can request one from any active order.</p>
                    </div>
                    @endforelse
                </div>

                {{-- ===== EXCHANGE REQUESTS ===== --}}
                <div class="fp-rq-card reveal-left" style="transition-delay:0.05s;">
                    <div class="fp-rq-header">
                        <h4><i class="bi bi-arrow-left-right"></i> Exchange Requests</h4>
                    </div>
                    @forelse($exchanges as $r)
                    <div class="fp-rq-item">
                        <div class="fp-rq-main">
                            <span class="fp-rq-title">
                                {{ Str::limit($r->currentProduct?->name ?? 'Ordered product', 35) }} <span class="fp-rq-arrow"><i class="bi bi-arrow-right"></i></span> {{ Str::limit($r->requestedProduct?->name ?? 'Wishlist item', 35) }}
                            </span>
                            <div class="fp-rq-sub">Order #{{ $r->order_id }} · {{ $r->created_at->format('M d, Y') }}</div>
                            @if($r->reason)<div class="fp-rq-reason">"{{ Str::limit($r->reason, 120) }}"</div>@endif
                            @if($r->admin_notes)
                            <div class="fp-rq-note"><i class="bi bi-chat-left-text-fill"></i> {{ $r->admin_notes }}</div>
                            @endif
                        </div>
                        <span class="fp-rq-chip {{ $r->status }}"><i class="bi {{ in_array($r->status, ['approved', 'completed']) ? 'bi-check-circle-fill' : ($r->status === 'rejected' ? 'bi-x-circle-fill' : 'bi-clock-fill') }}"></i> {{ ucfirst($r->status) }}</span>
                    </div>
                    @empty
                    <div class="fp-rq-empty">
                        <div class="fp-rq-empty-icon"><i class="bi bi-arrow-left-right"></i></div>
                        <p>No exchange requests yet. You can request one from any active order.</p>
                    </div>
                    @endforelse
                </div>

                {{-- ===== PRODUCT REQUESTS ===== --}}
                <div class="fp-rq-card reveal-left" style="transition-delay:0.1s;">
                    <div class="fp-rq-header">
                        <h4><i class="bi bi-plus-square-fill"></i> Product Requests</h4>
                        <a href="{{ route('requests.product.create') }}" class="fp-rq-action"><i class="bi bi-plus-lg"></i> Request a Product</a>
                    </div>
                    @forelse($productRequests as $r)
                    <div class="fp-rq-item">
                        <div class="fp-rq-main">
                            <span class="fp-rq-title">{{ $r->product_name }}</span>
                            <div class="fp-rq-sub">
                                {{ $r->created_at->format('M d, Y') }}
                                @if($r->product_url) · <a href="{{ $r->product_url }}" target="_blank" rel="noopener" style="color:var(--gold-400);">View link <i class="bi bi-box-arrow-up-right"></i></a>@endif
                            </div>
                            @if($r->reason)<div class="fp-rq-reason">"{{ Str::limit($r->reason, 120) }}"</div>@endif
                            @if($r->admin_notes)
                            <div class="fp-rq-note"><i class="bi bi-chat-left-text-fill"></i> {{ $r->admin_notes }}</div>
                            @endif
                        </div>
                        <span class="fp-rq-chip {{ $r->status }}"><i class="bi {{ in_array($r->status, ['approved', 'completed']) ? 'bi-check-circle-fill' : ($r->status === 'rejected' ? 'bi-x-circle-fill' : 'bi-clock-fill') }}"></i> {{ ucfirst(str_replace('_', ' ', $r->status)) }}</span>
                    </div>
                    @empty
                    <div class="fp-rq-empty">
                        <div class="fp-rq-empty-icon"><i class="bi bi-plus-square"></i></div>
                        <p>No product requests yet. Tell us what you'd love us to stock!</p>
                    </div>
                    @endforelse
                </div>

                {{-- ===== ACCOUNT CLOSURE ===== --}}
                @if($deletionRequest)
                <div class="fp-rq-card reveal-left" style="transition-delay:0.15s;">
                    <div class="fp-rq-header">
                        <h4><i class="bi bi-person-x" style="color:#f87171;"></i> Account Closure</h4>
                    </div>
                    <div class="fp-rq-item">
                        <div class="fp-rq-main">
                            <span class="fp-rq-title">Account closure request</span>
                            <div class="fp-rq-sub">Submitted {{ $deletionRequest->created_at->format('M d, Y') }}</div>
                            @if($deletionRequest->reason)<div class="fp-rq-reason">"{{ Str::limit($deletionRequest->reason, 120) }}"</div>@endif
                            @if($deletionRequest->admin_notes)
                            <div class="fp-rq-note"><i class="bi bi-chat-left-text-fill"></i> {{ $deletionRequest->admin_notes }}</div>
                            @endif
                        </div>
                        <span class="fp-rq-chip {{ $deletionRequest->status }}"><i class="bi {{ $deletionRequest->status === 'approved' ? 'bi-check-circle-fill' : ($deletionRequest->status === 'rejected' ? 'bi-x-circle-fill' : 'bi-clock-fill') }}"></i> {{ ucfirst($deletionRequest->status) }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
