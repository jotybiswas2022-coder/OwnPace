@extends('frontend.app')
@section('title', 'Request a Product — OwnPace Store')

@push('styles')
<style>
.fp-pc-hero {
    position: relative; padding: 50px 0 28px; overflow: hidden;
    background: linear-gradient(135deg, #0A0A0B 0%, #1A1A1E 30%, #0A0A0B 70%);
    border-bottom: 1px solid var(--card-border);
}
.fp-pc-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(234,179,8,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(234,179,8,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}
.fp-pc-orb {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -150px; right: -80px; pointer-events: none;
    animation: pcPulse 6s ease-in-out infinite;
}
@keyframes pcPulse { 0%,100%{transform:scale(1);opacity:0.4} 50%{transform:scale(1.15);opacity:0.8} }

.fp-pc-section { padding-bottom: 80px; min-height: 60vh; }
.fp-alert { display:flex;align-items:center;gap:10px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);color:#4ade80;padding:14px 20px;border-radius:var(--radius-sm);font-weight:500;font-size:13px;margin-bottom:24px;animation:alertSlide 0.4s ease-out; }
.fp-alert.error { background:rgba(239,68,68,0.08);border-color:rgba(239,68,68,0.25);color:#f87171; }
@keyframes alertSlide { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }

.fp-pc-card { background:var(--card-dark);border:1px solid var(--card-border);border-radius:var(--radius);overflow:hidden;transition:all 0.3s; }
.fp-pc-card:hover { border-color:rgba(234,179,8,0.15); }
.fp-pc-header { display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-bottom:1px solid var(--card-border);background:var(--surface-dark); }
.fp-pc-header h4 { font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:8px;margin:0; }
.fp-pc-header h4 i { color:var(--gold-500); }
.fp-pc-body { padding:24px; }

.fp-form-group label { display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px; }
.fp-form-group label i { color:var(--gold-500);font-size:13px; }
.fp-form-group label small { color:var(--text-dim);font-weight:400;font-size:11px; }
.fp-input { width:100%;padding:12px 16px;background:var(--surface-dark);border:1.5px solid var(--card-border);border-radius:var(--radius-sm);color:var(--text-primary);font-size:14px;font-family:inherit;outline:none;transition:all 0.25s ease; }
.fp-input:focus { border-color:var(--gold-500);box-shadow:0 0 0 3px rgba(234,179,8,0.08); }
.fp-input::placeholder { color:var(--text-dim); }

.fp-submit-btn {
    display:inline-flex; align-items:center; gap:8px;
    background:linear-gradient(135deg,var(--gold-500),var(--gold-600));
    color:var(--near-black); padding:13px 28px; border-radius:var(--radius-sm);
    font-weight:700; font-size:14px; border:none; cursor:pointer;
    transition:all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    font-family:inherit;
}
.fp-submit-btn:hover { transform:translateY(-2px); box-shadow:var(--shadow-gold); }
.fp-cancel-link { display:inline-flex; align-items:center; gap:6px; color:var(--text-muted); font-size:13px; font-weight:600; text-decoration:none; padding:13px 18px; border-radius:var(--radius-sm); transition:all 0.2s; }
.fp-cancel-link:hover { color:var(--gold-400); background:rgba(234,179,8,0.06); }

.fp-pc-track { display:flex; align-items:center; gap:0; margin-bottom:24px; }
.fp-pc-step { flex:1; text-align:center; position:relative; }
.fp-pc-step .fp-pc-dot {
    width:34px; height:34px; border-radius:50%; margin:0 auto 8px;
    display:flex; align-items:center; justify-content:center;
    background:var(--surface-dark); border:1px solid var(--card-border);
    color:var(--text-dim); font-size:14px;
}
.fp-pc-step .fp-pc-line {
    position:absolute; top:17px; left:calc(50% + 20px); right:calc(-50% + 20px);
    height:2px; background:var(--card-border);
}
.fp-pc-step:last-child .fp-pc-line { display:none; }
.fp-pc-step small { display:block; color:var(--text-dim); font-size:10px; text-transform:uppercase; letter-spacing:0.5px; }
.fp-pc-step.done .fp-pc-dot { background:linear-gradient(135deg,var(--gold-500),var(--gold-600)); color:var(--near-black); border-color:transparent; }
.fp-pc-step.done .fp-pc-line { background:var(--gold-500); }

@media (max-width: 768px) {
    .fp-pc-hero { padding: 36px 0 20px; }
}
</style>
@endpush

@section('content')
<section class="fp-pc-hero">
    <div class="fp-pc-hero-grid" aria-hidden="true"></div>
    <div class="fp-pc-orb" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-plus-square-fill"></i> Product Request</div>
            <h2>Request a Product</h2>
            <p>Can't find what you're looking for? Tell us and we'll try to stock it</p>
        </div>
    </div>
</section>

<section class="fp-pc-section">
    <div class="container">
        @if(session('error'))
        <div class="fp-alert error reveal-up"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
        @endif
        @if($errors->any())
        <div class="fp-alert error reveal-up">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-3">
                @include('frontend.partials.account-sidebar')
            </div>

            <div class="col-lg-9">
                <div class="fp-pc-track reveal-up">
                    @php
                        $steps = [
                            ['label' => 'Submitted', 'icon' => 'bi-send-fill'],
                            ['label' => 'Under review', 'icon' => 'bi-search'],
                            ['label' => 'Approved / Rejected', 'icon' => 'bi-check2-circle'],
                        ];
                    @endphp
                    @foreach($steps as $i => $st)
                    <div class="fp-pc-step {{ $i === 0 ? 'done' : '' }}">
                        <div class="fp-pc-line"></div>
                        <div class="fp-pc-dot"><i class="bi {{ $st['icon'] }}"></i></div>
                        <small>{{ $st['label'] }}</small>
                    </div>
                    @endforeach
                </div>

                <div class="fp-pc-card reveal-left">
                    <div class="fp-pc-header">
                        <h4><i class="bi bi-box-seam-fill"></i> New Product Request</h4>
                    </div>
                    <div class="fp-pc-body">
                        <form method="POST" action="{{ route('requests.product.store') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="fp-form-group">
                                        <label><i class="bi bi-box-fill"></i> Product Name <small>(required)</small></label>
                                        <input type="text" name="product_name" class="fp-input" value="{{ old('product_name') }}" placeholder="e.g. Apple MacBook Air M3" required maxlength="255">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="fp-form-group">
                                        <label><i class="bi bi-card-text"></i> Description</label>
                                        <textarea name="description" class="fp-input" rows="3" placeholder="Tell us a bit about the product — specs, features, model..." maxlength="2000">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="fp-form-group">
                                        <label><i class="bi bi-link-45deg"></i> Link</label>
                                        <input type="url" name="product_url" class="fp-input" value="{{ old('product_url') }}" placeholder="https://example.com/product (optional)">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="fp-form-group">
                                        <label><i class="bi bi-heart-fill"></i> Why do you want it?</label>
                                        <textarea name="reason" class="fp-input" rows="3" placeholder="Share why you'd love this product — we use this to prioritize requests (optional)" maxlength="1000">{{ old('reason') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12 d-flex align-items-center mt-2" style="gap:10px;">
                                    <button type="submit" class="fp-submit-btn"><i class="bi bi-send-fill"></i> Submit Request</button>
                                    <a href="{{ route('requests.index') }}" class="fp-cancel-link"><i class="bi bi-arrow-left"></i> Back to requests</a>
                                </div>
                                <p style="font-size:12px;color:var(--text-dim);">
                                    <i class="bi bi-clock-history" style="color:var(--gold-500);"></i>
                                    You'll see the status here as it moves from submitted → under review → approved or rejected.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
