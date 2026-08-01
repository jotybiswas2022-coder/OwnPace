@extends('frontend.app')
@section('title', 'Terms & Conditions — OwnPace Store')

@push('styles')
<style>
.fp-tr-hero {
    position: relative; padding: 40px 0 20px; overflow: hidden; isolation: isolate;
    background: linear-gradient(180deg, rgba(234,179,8,0.03) 0%, transparent 100%);
}
.fp-tr-orb {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -150px; right: -80px; pointer-events: none;
    animation: trPulse 4s ease-in-out infinite;
}
@keyframes trPulse { 0%,100%{transform:scale(1);opacity:0.5} 50%{transform:scale(1.1);opacity:1} }

.fp-tr-section { padding-bottom: 80px; }

.fp-tr-content {
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius); padding: 36px;
    color: var(--text-muted); line-height: 1.8; font-size: 15px;
    transition: all 0.3s; contain: layout style;
}
.fp-tr-content:hover { border-color: rgba(234,179,8,0.2); }
.fp-tr-content h4 { color: var(--text-primary); font-family: 'Syne', sans-serif; margin-bottom: 16px; font-size: 18px; }
.fp-tr-content h5 { color: var(--text-primary); margin-top: 28px; margin-bottom: 10px; font-size: 15px; }
.fp-tr-content p { margin-bottom: 12px; }
</style>
@endpush

@section('content')
<section class="fp-tr-hero">
    <div class="fp-tr-orb" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-file-earmark-text-fill"></i> Legal</div>
            <h2>{{ $type == 'payment' ? 'Payment Terms' : ($type == 'delivery' ? 'Delivery Policy' : ($type == 'privacy' ? 'Privacy Policy' : 'Terms & Conditions')) }}</h2>
            <p>Please read these terms carefully before using our services</p>
        </div>
    </div>
</section>

<section class="fp-tr-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="fp-tr-content reveal-up">
                    @if(isset($terms) && $terms)
                        {!! nl2br(e($terms->content)) !!}
                    @else
                        <h4>General Terms</h4>
                        <p>By using OwnPace Store, you agree to the following terms and conditions. Please read them carefully.</p>

                        <h5>1. Payment Plans</h5>
                        <p>OwnPace offers flexible installment payment plans ranging from 4 to 40 weeks or 1 to 12 months. Interest rates vary by plan. By selecting a plan, you commit to making timely payments as agreed.</p>

                        <h5>2. Delivery</h5>
                        <p>Products will be shipped once 70% of the total order is paid. Delivery fees are calculated and included in the initial payment. Delivery timelines vary by location.</p>

                        <h5>3. Cancellation</h5>
                        <p>You may cancel your installment plan at any time. A 10% cancellation fee applies. Refunds are credited to your OwnPace wallet for future purchases.</p>

                        <h5>4. Insurance</h5>
                        <p>Insurance is optional and costs 10% of the total order. It covers damage, loss, or theft during the installment period.</p>

                        <h5>5. Account</h5>
                        <p>You are responsible for maintaining the confidentiality of your account credentials. OwnPace reserves the right to suspend or terminate accounts for violation of terms.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@include('frontend.partials.footer')
@endsection
