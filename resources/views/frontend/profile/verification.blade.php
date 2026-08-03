@extends('frontend.app')
@section('title', 'Verification — OwnPace Store')

@push('styles')
<style>
/* ===== VERIFICATION HERO ===== */
.fp-vr-hero {
    position: relative; padding: 50px 0 28px; overflow: hidden;
    background: linear-gradient(135deg, #0A0A0B 0%, #1A1A1E 30%, #0A0A0B 70%);
    border-bottom: 1px solid var(--card-border);
}
.fp-vr-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(234,179,8,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(234,179,8,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}
.fp-vr-orb {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -150px; right: -80px; pointer-events: none;
    animation: vrPulse 6s ease-in-out infinite;
}
@keyframes vrPulse { 0%,100%{transform:scale(1);opacity:0.4} 50%{transform:scale(1.15);opacity:0.8} }

.fp-vr-section { padding-bottom: 80px; min-height: 60vh; }
.fp-alert { display:flex;align-items:center;gap:10px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);color:#4ade80;padding:14px 20px;border-radius:var(--radius-sm);font-weight:500;font-size:13px;margin-bottom:24px;animation:alertSlide 0.4s ease-out; }
@keyframes alertSlide { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }

.fp-verification-card {
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius); padding: 28px 24px;
    text-align: center; height: 100%;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative; overflow: hidden;
}
.fp-verification-card::after {
    content: ''; position: absolute; inset: 0;
    border-radius: var(--radius);
    pointer-events: none; opacity: 0;
    transition: opacity 0.4s;
    box-shadow: inset 0 0 0 1px rgba(234,179,8,0.15);
}
.fp-verification-card:hover::after { opacity: 1; }
.fp-verification-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 36px rgba(0,0,0,0.3);
}
.fp-verification-card.status-approved { border-color: rgba(34,197,94,0.35); }
.fp-verification-card.status-pending { border-color: rgba(234,179,8,0.35); }
.fp-verification-card.status-rejected { border-color: rgba(239,68,68,0.35); }
.fp-v-icon {
    width: 52px; height: 52px; border-radius: 12px;
    margin: 0 auto 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
}
.status-approved .fp-v-icon { background: rgba(34,197,94,0.1); color: #4ade80; }
.status-pending .fp-v-icon { background: rgba(234,179,8,0.1); color: var(--gold-400); }
.status-rejected .fp-v-icon { background: rgba(239,68,68,0.1); color: #f87171; }
.fp-verification-card h5 { color: var(--text-primary); font-size: 15px; font-weight: 600; margin-bottom: 10px; }

.fp-v-status {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 14px; border-radius: 6px;
    font-size: 12px; font-weight: 600;
}
.fp-v-status.approved { background: rgba(34,197,94,0.12); color: #4ade80; }
.fp-v-status.pending { background: rgba(234,179,8,0.12); color: var(--gold-400); }
.fp-v-status.rejected { background: rgba(239,68,68,0.12); color: #f87171; }

.fp-v-reject {
    background: rgba(239,68,68,0.08);
    border: 1px solid rgba(239,68,68,0.2);
    border-radius: 8px; padding: 10px 12px;
    margin-top: 12px; font-size: 11px;
    color: #f87171; text-align: left;
}
.fp-v-reject strong { display: block; margin-bottom: 3px; }

.fp-v-file {
    width: 100%; padding: 8px 10px;
    font-size: 12px; color: var(--text-muted);
}
.fp-v-submit {
    width: 100%; padding: 10px;
    background: rgba(234,179,8,0.1);
    border: 1px solid rgba(234,179,8,0.2);
    border-radius: 8px;
    color: var(--gold-400);
    font-size: 12px; font-weight: 600;
    cursor: pointer; transition: all 0.3s;
    font-family: inherit;
}
.fp-v-submit:hover {
    background: rgba(234,179,8,0.2);
    border-color: rgba(234,179,8,0.3);
}

@media (max-width: 768px) {
    .fp-vr-hero { padding: 36px 0 20px; }
}
</style>
@endpush

@section('content')
<section class="fp-vr-hero">
    <div class="fp-vr-hero-grid" aria-hidden="true"></div>
    <div class="fp-vr-orb" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-patch-check-fill"></i> Account Verification</div>
            <h2>Verification Status</h2>
            <p>Complete your verification to unlock all features</p>
        </div>
    </div>
</section>

<section class="fp-vr-section">
    <div class="container">
        @if(session('success'))
        <div class="fp-alert reveal-up"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-3">
                @include('frontend.partials.account-sidebar')
            </div>
            <div class="col-lg-9">
                <div class="row g-4">
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

            @foreach($vTypes as $vt)
            @php
                $verification = $verifications->firstWhere('type', $vt['key']);
                $status = $verification?->status ?? 'unsubmitted';
            @endphp
            <div class="col-lg-4 col-md-6">
                <div class="fp-verification-card status-{{ $status }} reveal-up">
                    <div class="fp-v-icon"><i class="bi {{ $vt['icon'] }}"></i></div>
                    <h5>{{ $vt['label'] }}</h5>
                    <span class="fp-v-status {{ $status }}">
                        @if($status == 'approved') <i class="bi bi-check-circle-fill"></i> Approved
                        @elseif($status == 'pending') <i class="bi bi-clock-fill"></i> Pending Review
                        @elseif($status == 'rejected') <i class="bi bi-x-circle-fill"></i> Rejected
                        @else <i class="bi bi-dash-circle-fill"></i> Not Submitted
                        @endif
                    </span>
                    @if($verification?->rejection_reason)
                        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);border-radius:6px;padding:8px 10px;margin-top:10px;font-size:11px;color:#ef4444;text-align:left;">
                            <strong>Reason:</strong> {{ $verification->rejection_reason }}
                        </div>
                    @endif
                    @if(in_array($status, ['unsubmitted', 'rejected']) && $vt['key'] != 'email' && $vt['key'] != 'store_terms')
                        <form action="{{ route('profile.verification.submit') }}" method="POST" class="mt-3" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="{{ $vt['key'] }}">
                            <input type="text" name="document_number" class="fp-v-file" placeholder="Document number (optional)" style="margin-bottom:6px;background:#121214;border:1px solid #2A2A2E;border-radius:6px;padding:8px 10px;color:#F4F4F5;font-size:12px;">
                            <input type="file" name="document" class="fp-v-file" required {{ $status == 'rejected' ? '' : '' }}>
                            <button type="submit" class="fp-v-submit mt-2">{{ $status == 'rejected' ? 'Resubmit' : 'Upload & Submit' }}</button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@include('frontend.partials.footer')
@endsection
