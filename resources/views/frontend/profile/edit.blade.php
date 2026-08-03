@extends('frontend.app')
@section('title', 'Edit Profile — OwnPace Store')

@push('styles')
<style>
/* ===== SETTINGS HERO ===== */
.fp-ed-hero {
    position: relative; padding: 50px 0 28px; overflow: hidden;
    background: linear-gradient(135deg, #0A0A0B 0%, #1A1A1E 30%, #0A0A0B 70%);
    border-bottom: 1px solid var(--card-border);
}
.fp-ed-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(234,179,8,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(234,179,8,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}
.fp-ed-orb {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -150px; right: -80px; pointer-events: none;
    animation: edPulse 6s ease-in-out infinite;
}
@keyframes edPulse { 0%,100%{transform:scale(1);opacity:0.4} 50%{transform:scale(1.15);opacity:0.8} }

.fp-ed-section { padding-bottom: 80px; min-height: 60vh; }
.fp-alert { display:flex;align-items:center;gap:10px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);color:#4ade80;padding:14px 20px;border-radius:var(--radius-sm);font-weight:500;font-size:13px;margin-bottom:24px;animation:alertSlide 0.4s ease-out; }
@keyframes alertSlide { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }

.fp-form-group label { display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px; }
.fp-form-group label i { color:var(--gold-500);font-size:13px; }
.fp-input { width:100%;padding:12px 16px;background:var(--surface-dark);border:1.5px solid var(--card-border);border-radius:var(--radius-sm);color:var(--text-primary);font-size:14px;font-family:inherit;outline:none;transition:all 0.25s ease; }
.fp-input:focus { border-color:var(--gold-500);box-shadow:0 0 0 3px rgba(234,179,8,0.08); }
.fp-input::placeholder { color:var(--text-dim); }

.fp-card { background:var(--card-dark);border:1px solid var(--card-border);border-radius:var(--radius);overflow:hidden;transition:all 0.3s ease; }
.fp-card:hover { border-color:rgba(234,179,8,0.15);box-shadow:var(--shadow-glow-sm); }
.fp-card-header { padding:18px 24px;border-bottom:1px solid var(--card-border);background:var(--surface-dark); }
.fp-card-header h4 { font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:8px;margin:0; }
.fp-card-header h4 i { color:var(--gold-500); }
.fp-card-body { padding:24px; }

@media (max-width: 991px) { .fp-ed-hero { padding:36px 0 20px; } }
</style>
@endpush

@section('content')
<section class="fp-ed-hero">
    <div class="fp-ed-hero-grid" aria-hidden="true"></div>
    <div class="fp-ed-orb" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-gear-fill"></i> Settings</div>
            <h2>Account Settings</h2>
            <p>Update your personal information and security</p>
        </div>
    </div>
</section>

<section class="fp-ed-section">
    <div class="container">
        @if(session('success'))
        <div class="fp-alert reveal-up"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-3">
                @include('frontend.partials.account-sidebar')
            </div>

            <div class="col-lg-9">
                <div class="fp-card reveal-left" style="transition-delay:0.1s;">
                    <div class="fp-card-header">
                        <h4><i class="bi bi-pencil-fill"></i> Edit Personal Information</h4>
                    </div>
                    <div class="fp-card-body">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="fp-form-group">
                                        <label><i class="bi bi-person-fill"></i> Full Name</label>
                                        <input type="text" name="name" class="fp-input" value="{{ auth()->user()->name }}" placeholder="Your full name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="fp-form-group">
                                        <label><i class="bi bi-envelope-fill"></i> Email (read-only)</label>
                                        <input type="email" class="fp-input" value="{{ auth()->user()->email }}" disabled style="opacity:0.6;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="fp-form-group">
                                        <label><i class="bi bi-phone-fill"></i> Phone (read-only)</label>
                                        <input type="text" class="fp-input" value="{{ auth()->user()->phone ?? '—' }}" disabled style="opacity:0.6;" placeholder="+234 801 234 5678">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <small style="color:var(--text-dim);font-size:12px;"><i class="bi bi-info-circle-fill" style="color:var(--gold-500);"></i> Email and phone are locked for security. Contact support to update them.</small>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn-primary-gold"><i class="bi bi-check-lg"></i> Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="fp-card mt-4 reveal-left" style="transition-delay:0.2s;">
                    <div class="fp-card-header">
                        <h4><i class="bi bi-shield-lock-fill"></i> Change Password</h4>
                    </div>
                    <div class="fp-card-body">
                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="fp-form-group">
                                        <label>Current Password</label>
                                        <input type="password" name="current_password" class="fp-input" placeholder="••••••••">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="fp-form-group">
                                        <label>New Password</label>
                                        <input type="password" name="password" class="fp-input" placeholder="••••••••">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="fp-form-group">
                                        <label>Confirm New Password</label>
                                        <input type="password" name="password_confirmation" class="fp-input" placeholder="••••••••">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn-primary-gold"><i class="bi bi-check-lg"></i> Update Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
