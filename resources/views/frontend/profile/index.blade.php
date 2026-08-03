@extends('frontend.app')
@section('title', 'My Profile — OwnPace Store')

@push('styles')
<style>
/* ===== PROFILE HERO ===== */
.fp-prof-hero {
    position: relative; padding: 50px 0 28px; overflow: hidden;
    background: linear-gradient(135deg, #0A0A0B 0%, #1A1A1E 30%, #0A0A0B 70%);
    border-bottom: 1px solid var(--card-border);
}
.fp-prof-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(234,179,8,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(234,179,8,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}
.fp-prof-orb {
    position: absolute; width: 450px; height: 450px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -180px; right: -100px; pointer-events: none;
    animation: prfPulse 6s ease-in-out infinite;
}
@keyframes prfPulse { 0%,100%{transform:scale(1);opacity:0.4} 50%{transform:scale(1.15);opacity:0.8} }

.fp-prof-section { padding-bottom: 80px; min-height: 60vh; }

.fp-alert {
    display: flex; align-items: center; gap: 10px;
    background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.2);
    color: #4ade80; padding: 14px 20px; border-radius: var(--radius-sm);
    font-weight: 500; font-size: 13px; margin-bottom: 24px;
    animation: alertSlide 0.4s ease-out;
}
.fp-alert.error { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.25); color: #f87171; }
.fp-alert.info { background: rgba(59,130,246,0.08); border-color: rgba(59,130,246,0.25); color: #60a5fa; }
@keyframes alertSlide {
    from { opacity:0; transform: translateY(-10px); }
    to { opacity:1; transform: translateY(0); }
}

/* ===== STATS ===== */
.fp-stat-mini {
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius-sm); padding: 18px 16px;
    display: flex; align-items: center; gap: 14px;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.fp-stat-mini:hover {
    border-color: rgba(234,179,8,0.2);
    transform: translateY(-3px);
    box-shadow: var(--shadow-glow-sm);
}
.fp-stat-mini i { font-size: 28px; color: var(--gold-500); }
.fp-stat-mini strong { display: block; color: var(--text-primary); font-size: 18px; font-weight: 700; font-family: 'Syne', sans-serif; }
.fp-stat-mini span { color: var(--text-dim); font-size: 12px; }

/* ===== CARDS ===== */
.fp-profile-card {
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius); overflow: hidden;
    transition: all 0.3s ease;
}
.fp-profile-card:hover {
    border-color: rgba(234,179,8,0.15);
    box-shadow: var(--shadow-glow-sm);
}
.fp-profile-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 24px;
    border-bottom: 1px solid var(--card-border);
    background: var(--surface-dark);
}
.fp-profile-card-header h4 {
    font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700;
    color: var(--text-primary); display: flex; align-items: center; gap: 8px; margin: 0;
}
.fp-profile-card-header h4 i { color: var(--gold-500); }
.btn-edit {
    font-size: 12px; color: var(--gold-400); font-weight: 600;
    display: flex; align-items: center; gap: 4px;
    text-decoration: none; padding: 6px 12px;
    border-radius: 6px; transition: all 0.3s;
}
.btn-edit:hover { background: rgba(234,179,8,0.08); color: var(--gold-300); }
.fp-profile-card-body { padding: 24px; }

.fp-info-item label {
    display: block; font-size: 11px; color: var(--text-dim);
    font-weight: 500; margin-bottom: 4px;
    text-transform: uppercase; letter-spacing: 0.5px;
}
.fp-info-item span {
    color: var(--text-primary); font-size: 15px; font-weight: 500;
    word-break: break-word;
}

/* ===== VERIFICATION PANEL ===== */
.fp-vp-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
.fp-vp-item {
    background: var(--surface-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius-sm); padding: 14px;
    display: flex; align-items: center; gap: 12px;
    transition: all 0.3s;
}
.fp-vp-item:hover { border-color: rgba(234,179,8,0.2); transform: translateY(-2px); }
.fp-vp-icon {
    width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 17px;
}
.fp-vp-item.v-approved .fp-vp-icon { background: rgba(34,197,94,0.12); color: #4ade80; }
.fp-vp-item.v-pending .fp-vp-icon { background: rgba(234,179,8,0.12); color: var(--gold-400); }
.fp-vp-item.v-rejected .fp-vp-icon { background: rgba(239,68,68,0.12); color: #f87171; }
.fp-vp-item.v-unsubmitted .fp-vp-icon { background: rgba(148,163,184,0.1); color: var(--text-dim); }
.fp-vp-label { display: block; color: var(--text-primary); font-size: 13px; font-weight: 600; }
.fp-vp-status { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 600; }
.fp-vp-item.v-approved .fp-vp-status { color: #4ade80; }
.fp-vp-item.v-pending .fp-vp-status { color: var(--gold-400); }
.fp-vp-item.v-rejected .fp-vp-status { color: #f87171; }
.fp-vp-item.v-unsubmitted .fp-vp-status { color: var(--text-dim); }

/* ===== RECENT ORDERS ===== */
.fp-order-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 24px; border-bottom: 1px solid var(--card-border);
    transition: background 0.2s;
}
.fp-order-row:last-child { border-bottom: none; }
.fp-order-row:hover { background: rgba(234,179,8,0.03); }
.fp-order-status-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 6px;
    font-size: 11px; font-weight: 600; text-transform: uppercase;
}
.fp-order-status-badge.in-progress { background: rgba(234,179,8,0.12); color: var(--gold-400); }
.fp-order-status-badge.completed { background: rgba(34,197,94,0.12); color: #4ade80; }
.fp-order-status-badge.cancelled { background: rgba(239,68,68,0.12); color: #f87171; }

.fp-order-empty {
    text-align: center; padding: 32px 20px;
}
.fp-order-empty-icon {
    width: 48px; height: 48px; border-radius: 50%;
    background: var(--surface-dark);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 8px; font-size: 20px; color: var(--text-dim);
}
.fp-order-empty p { color: var(--text-dim); font-size: 13px; margin: 0; }
.fp-order-empty a { color: var(--gold-400); }

/* ===== CLOSURE CARD ===== */
.fp-closure-note { font-size: 13px; color: var(--text-muted); line-height: 1.6; margin: 0; }
.fp-input {
    width: 100%; padding: 12px 16px; background: var(--surface-dark);
    border: 1.5px solid var(--card-border); border-radius: var(--radius-sm);
    color: var(--text-primary); font-size: 14px; font-family: inherit;
    outline: none; transition: all 0.25s ease;
}
.fp-input:focus { border-color: var(--gold-500); box-shadow: 0 0 0 3px rgba(234,179,8,0.08); }
.fp-input::placeholder { color: var(--text-dim); }
.fp-closure-state {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 18px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;
}
.fp-closure-state.pending { background: rgba(234,179,8,0.1); color: var(--gold-400); border: 1px solid rgba(234,179,8,0.25); }
.fp-closure-state.approved { background: rgba(239,68,68,0.1); color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
.fp-closure-state.rejected { background: rgba(34,197,94,0.1); color: #4ade80; border: 1px solid rgba(34,197,94,0.3); }
.fp-action-btn {
    padding: 12px 20px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: all 0.2s; font-family: inherit; border: 1px solid rgba(239,68,68,0.3);
    background: rgba(239,68,68,0.05); color: #ef4444; text-decoration: none;
}
.fp-action-btn:hover { background: rgba(239,68,68,0.12); border-color: #ef4444; }

@media (max-width: 991px) {
    .fp-prof-hero { padding: 36px 0 20px; }
}
@media (max-width: 767px) {
    .fp-vp-grid { grid-template-columns: repeat(2, 1fr); }
    .fp-order-row { flex-direction: column; align-items: flex-start; gap: 8px; }
}
@media (max-width: 480px) {
    .fp-vp-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<section class="fp-prof-hero">
    <div class="fp-prof-hero-grid" aria-hidden="true"></div>
    <div class="fp-prof-orb" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-person-circle"></i> My Profile</div>
            <h2>Welcome, {{ auth()->user()->name ?? auth()->user()->email }}</h2>
            <p>Manage your account settings and view your activity</p>
        </div>
    </div>
</section>

<section class="fp-prof-section">
    <div class="container">
        @if(session('success'))
        <div class="fp-alert reveal-up"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="fp-alert error reveal-up"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
        @endif
        @if(session('info'))
        <div class="fp-alert info reveal-up"><i class="bi bi-info-circle-fill"></i> {{ session('info') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-3">
                @include('frontend.partials.account-sidebar')
            </div>

            <div class="col-lg-9">
                <div class="row g-3 mb-4 reveal-up">
                    <div class="col-md-4 col-6">
                        <div class="fp-stat-mini">
                            <i class="bi bi-box-seam-fill"></i>
                            <div>
                                <strong>{{ auth()->user()->orders()->count() }}</strong>
                                <span>Orders</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="fp-stat-mini">
                            <i class="bi bi-coin"></i>
                            <div>
                                <strong>{{ auth()->user()->installmentPayments()->count() }}</strong>
                                <span>Installments</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="fp-stat-mini">
                            <i class="bi bi-wallet2"></i>
                            <div>
                                <strong>₦{{ number_format(auth()->user()->wallet?->balance ?? 0, 0) }}</strong>
                                <span>Wallet Balance</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fp-profile-card reveal-left" style="transition-delay:0.1s;">
                    <div class="fp-profile-card-header">
                        <h4><i class="bi bi-person-fill"></i> Personal Information</h4>
                        <a href="{{ route('profile.edit') }}" class="btn-edit"><i class="bi bi-pencil-fill"></i> Edit</a>
                    </div>
                    <div class="fp-profile-card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="fp-info-item">
                                    <label>Full Name</label>
                                    <span>{{ auth()->user()->name ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fp-info-item">
                                    <label>Email Address</label>
                                    <span>{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fp-info-item">
                                    <label>Phone Number</label>
                                    <span>{{ auth()->user()->phone ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fp-info-item">
                                    <label>Member Since</label>
                                    <span>{{ auth()->user()->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fp-profile-card mt-4 reveal-left" style="transition-delay:0.15s;">
                    <div class="fp-profile-card-header">
                        <h4><i class="bi bi-patch-check-fill"></i> Verification Status</h4>
                        <a href="{{ route('profile.verification') }}" class="btn-edit">Manage</a>
                    </div>
                    <div class="fp-profile-card-body">
                        @php
                            $vTypes = [
                                ['key' => 'identity_card', 'icon' => 'bi-person-badge-fill', 'label' => 'Identity Card'],
                                ['key' => 'payment_card', 'icon' => 'bi-credit-card-2-front-fill', 'label' => 'Payment Card'],
                                ['key' => 'bank_account', 'icon' => 'bi-bank2', 'label' => 'Bank Account'],
                                ['key' => 'email', 'icon' => 'bi-envelope-fill', 'label' => 'Email Address'],
                                ['key' => 'store_terms', 'icon' => 'bi-file-earmark-text-fill', 'label' => 'Store Terms'],
                                ['key' => 'delivery_address', 'icon' => 'bi-geo-alt-fill', 'label' => 'Delivery Address'],
                            ];
                        @endphp
                        <div class="fp-vp-grid">
                            @foreach($vTypes as $vt)
                            @php
                                $st = $verificationStatuses[$vt['key']] ?? 'unsubmitted';
                                $chip = [
                                    'approved' => ['bi-check-circle-fill', 'Verified'],
                                    'pending' => ['bi-clock-fill', 'Pending'],
                                    'rejected' => ['bi-x-circle-fill', 'Rejected'],
                                    'unsubmitted' => ['bi-dash-circle-fill', 'Not Submitted'],
                                ][$st] ?? ['bi-dash-circle-fill', 'Not Submitted'];
                            @endphp
                            <div class="fp-vp-item v-{{ $st }}">
                                <div class="fp-vp-icon"><i class="bi {{ $vt['icon'] }}"></i></div>
                                <div>
                                    <span class="fp-vp-label">{{ $vt['label'] }}</span>
                                    <span class="fp-vp-status"><i class="bi {{ $chip[0] }}"></i> {{ $chip[1] }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="fp-profile-card mt-4 reveal-left" style="transition-delay:0.2s;border-color:rgba(239,68,68,0.15);">
                    <div class="fp-profile-card-header">
                        <h4><i class="bi bi-person-x" style="color:#f87171;"></i> Account Closure</h4>
                    </div>
                    <div class="fp-profile-card-body">
                        @if(isset($deletionRequest) && $deletionRequest->status === 'pending')
                            <div class="fp-closure-state pending">
                                <i class="bi bi-hourglass-split"></i> Your account closure request is under review.
                            </div>
                            <p class="fp-closure-note mt-3" style="margin-top:12px;">
                                We'll get back to you once it's processed. You can keep using your account until then.
                            </p>
                        @elseif(isset($deletionRequest) && $deletionRequest->status === 'approved')
                            <div class="fp-closure-state approved">
                                <i class="bi bi-check-circle-fill"></i> Your account closure request was approved.
                            </div>
                            @if($deletionRequest->admin_notes)
                            <p class="fp-closure-note mt-3">Note: {{ $deletionRequest->admin_notes }}</p>
                            @endif
                        @else
                            <p class="fp-closure-note">
                                Closing your account is permanent and can't be undone. Any remaining wallet balance and
                                active plans must be settled first. This request is reviewed by our team — it's not
                                processed automatically.
                            </p>
                            @if(auth()->user()->activeOrders()->count() > 0)
                                <div class="fp-alert info mt-3" style="margin-bottom:0;">
                                    <i class="bi bi-info-circle-fill"></i> You have active orders. Finish them before requesting closure.
                                </div>
                            @else
                                <form method="POST" action="{{ route('profile.deletion.request') }}" class="mt-3"
                                      onsubmit="return confirm('Request account closure? This is reviewed by our team before anything is deleted.')">
                                    @csrf
                                    <textarea name="reason" class="fp-input" rows="3"
                                              placeholder="Tell us why you're leaving (optional)"></textarea>
                                    <button type="submit" class="fp-action-btn danger" style="margin-top:12px;display:inline-flex;">
                                        <i class="bi bi-person-x"></i> Request Account Closure
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="fp-profile-card mt-4 reveal-left" style="transition-delay:0.25s;">
                    <div class="fp-profile-card-header">
                        <h4><i class="bi bi-box-seam-fill"></i> Recent Orders</h4>
                        <a href="{{ route('orders.index') }}" class="btn-edit">View All</a>
                    </div>
                    <div class="fp-profile-card-body p-0">
                        @php $recentOrders = auth()->user()->orders()->latest()->take(5)->get(); @endphp
                        @forelse($recentOrders as $order)
                        @php $badge = orderProgressBadge($order); @endphp
                        <div class="fp-order-row">
                            <div class="d-flex align-items-center gap-3">
                                <div class="fp-order-status-badge {{ $badge['class'] }}"><i class="bi {{ $badge['icon'] }}"></i> {{ $badge['label'] }}</div>
                                <div>
                                    <strong style="color:var(--text-primary);">Order #{{ $order->id }}</strong>
                                    <small style="color:var(--text-dim);display:block;">{{ $order->created_at->format('M d, Y') }}</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span style="color:var(--gold-400);font-weight:700;font-family:'Syne',sans-serif;">₦{{ number_format($order->grand_total, 0) }}</span>
                                <a href="{{ route('orders.show', $order) }}" class="btn-edit"><i class="bi bi-eye"></i> View</a>
                            </div>
                        </div>
                        @empty
                        <div class="fp-order-empty">
                            <div class="fp-order-empty-icon"><i class="bi bi-inbox"></i></div>
                            <p>No orders yet. <a href="{{ url('/shop') }}">Start shopping!</a></p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
