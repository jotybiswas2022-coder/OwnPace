@extends('frontend.app')
@section('title', 'About Us — OwnPace Store')

@push('styles')
<style>
.fp-ab-hero {
    position: relative; padding: 60px 0 80px; overflow: hidden; isolation: isolate;
    background: linear-gradient(180deg, rgba(234,179,8,0.03) 0%, transparent 100%);
}
.fp-ab-orb {
    position: absolute; width: 600px; height: 600px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.05) 0%, transparent 60%);
    top: -200px; right: -100px; pointer-events: none;
    animation: abOrbPulse 4s ease-in-out infinite;
}
.fp-ab-orb2 {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.03) 0%, transparent 60%);
    bottom: -100px; left: -80px; pointer-events: none;
    animation: abOrbPulse 5s ease-in-out infinite reverse;
}
@keyframes abOrbPulse { 0%,100%{transform:scale(1);opacity:0.5} 50%{transform:scale(1.1);opacity:1} }

.fp-ab-story h3 {
    font-family: 'Syne', sans-serif; color: var(--text-primary); margin-bottom: 16px;
}
.fp-ab-story p {
    color: var(--text-muted); line-height: 1.8; margin-bottom: 16px; font-size: 15px;
}

.fp-ab-stat-card {
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius-sm); padding: 28px 20px; text-align: center;
    transition: all 0.4s; contain: layout style;
}
.fp-ab-stat-card:hover {
    border-color: rgba(234,179,8,0.25); transform: translateY(-4px);
    box-shadow: var(--shadow-card-hover);
}
.fp-ab-stat-card i { font-size: 32px; color: var(--gold-500); display: block; margin-bottom: 10px; }

.fp-ab-mission {
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius-lg); padding: 48px;
    position: relative; overflow: hidden;
}
.fp-ab-mission::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--gold-500), var(--gold-400), var(--gold-600));
}
.fp-ab-mission-icon {
    width: 60px; height: 60px; border-radius: 16px;
    background: rgba(234,179,8,0.1); border: 1px solid rgba(234,179,8,0.2);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold-500); font-size: 26px; margin-bottom: 20px;
}
.fp-ab-mission h4 { font-family: 'Syne', sans-serif; color: var(--text-primary); margin-bottom: 12px; }
.fp-ab-mission p { color: var(--text-muted); line-height: 1.8; }

.fp-ab-value-card {
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius); padding: 32px 24px; text-align: center;
    transition: all 0.4s; height: 100%; touch-action: manipulation;
    contain: layout style;
}
.fp-ab-value-card:hover {
    border-color: rgba(234,179,8,0.2); transform: translateY(-4px);
    box-shadow: var(--shadow-card-hover);
}
.fp-ab-value-icon {
    width: 56px; height: 56px; border-radius: 14px;
    background: rgba(234,179,8,0.08); border: 1px solid rgba(234,179,8,0.15);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px; color: var(--gold-500); font-size: 24px;
}
.fp-ab-value-card h5 { font-family: 'Syne', sans-serif; color: var(--text-primary); font-size: 15px; margin-bottom: 6px; }
.fp-ab-value-card p { color: var(--text-dim); font-size: 13px; line-height: 1.6; }
.fp-ab-value-tagline { display: block; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 9px; opacity: 0.7; }
.fp-ab-value-icon.is-trust { background: rgba(234,179,8,0.1); border-color: rgba(234,179,8,0.2); color: #eab308; }
.fp-ab-value-icon.is-flex { background: rgba(96,165,250,0.1); border-color: rgba(96,165,250,0.2); color: #60a5fa; }
.fp-ab-value-icon.is-speed { background: rgba(52,211,153,0.1); border-color: rgba(52,211,153,0.2); color: #34d399; }
.fp-ab-value-icon.is-reliable { background: rgba(167,139,250,0.1); border-color: rgba(167,139,250,0.2); color: #a78bfa; }
.fp-ab-value-icon.is-innovate { background: rgba(244,114,182,0.1); border-color: rgba(244,114,182,0.2); color: #f472b6; }
.fp-ab-value-icon.is-team { background: rgba(251,146,60,0.1); border-color: rgba(251,146,60,0.2); color: #fb923c; }

.fp-ab-timeline {
    position: relative; padding-left: 32px;
}
.fp-ab-timeline::before {
    content: ''; position: absolute; left: 8px; top: 0; bottom: 0;
    width: 2px; background: var(--card-border);
}
.fp-ab-tl-item {
    position: relative; padding-bottom: 32px;
    transition: transform 0.3s;
}
.fp-ab-tl-item:hover { transform: translateX(6px); }
.fp-ab-tl-item:last-child { padding-bottom: 0; }
.fp-ab-tl-dot {
    position: absolute; left: -28px; top: 4px;
    width: 14px; height: 14px; border-radius: 50%;
    background: var(--gold-500); border: 3px solid var(--card-dark);
    box-shadow: 0 0 0 2px var(--gold-500);
}
.fp-ab-tl-year {
    font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700;
    color: var(--gold-500); margin-bottom: 4px;
}
.fp-ab-tl-text { color: var(--text-dim); font-size: 14px; line-height: 1.6; }

.fp-ab-cta {
    background: linear-gradient(135deg, rgba(234,179,8,0.05), rgba(234,179,8,0.02));
    border: 1px solid rgba(234,179,8,0.15);
    border-radius: var(--radius-lg); padding: 48px;
    text-align: center; position: relative; overflow: hidden;
}
.fp-ab-cta h3 { font-family: 'Syne', sans-serif; color: var(--text-primary); margin-bottom: 8px; }
.fp-ab-cta p { color: var(--text-muted); margin-bottom: 24px; }

@media (max-width: 768px) {
    .fp-ab-mission { padding: 28px 20px; }
    .fp-ab-cta { padding: 32px 20px; }
}
</style>
@endpush

@section('content')
<section class="fp-ab-hero">
    <div class="fp-ab-orb" aria-hidden="true"></div>
    <div class="fp-ab-orb2" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-info-circle-fill"></i> About Us</div>
            <h2>Why OwnPace?</h2>
            <p>We're on a mission to make shopping accessible for everyone</p>
        </div>

        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="fp-ab-story reveal-left">
                    <h3>Buy Now, Pay Comfortably</h3>
                    <p>OwnPace Store is Nigeria's premier installment payment platform. We believe everyone deserves access to quality products without financial strain. Our platform allows you to shop thousands of products and pay over time with flexible weekly or monthly plans that fit your budget.</p>
                    <p>With features like insurance coverage, wallet system, delivery tracking, and 24/7 support, we're here to make your shopping experience seamless and enjoyable. Our mission is simple: empower Nigerians to get what they need today while paying in a way that works for them.</p>
                    <p>Founded in 2025, we've quickly grown to serve thousands of happy customers across 36 states in Nigeria, partnering with top brands and trusted payment gateways to ensure a safe, secure shopping experience.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3 reveal-right">
                    <div class="col-6">
                        <div class="fp-ab-stat-card">
                            <i class="bi bi-people-fill"></i>
                            <div class="counter-num" data-count="5000" style="font-size:28px;">0</div>
                            <div style="color:var(--text-dim);font-size:13px;">Products</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fp-ab-stat-card">
                            <i class="bi bi-emoji-smile-fill"></i>
                            <div class="counter-num" data-count="15000" style="font-size:28px;">0</div>
                            <div style="color:var(--text-dim);font-size:13px;">Happy Customers</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fp-ab-stat-card">
                            <i class="bi bi-coin"></i>
                            <div class="counter-num" data-count="36" style="font-size:28px;">0</div>
                            <div style="color:var(--text-dim);font-size:13px;">Payment Plans</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fp-ab-stat-card">
                            <i class="bi bi-building"></i>
                            <div class="counter-num" data-count="36" style="font-size:28px;">0</div>
                            <div style="color:var(--text-dim);font-size:13px;">States Covered</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="padding-bottom:80px;">
    <div class="container">
        <div class="fp-ab-mission reveal-up mb-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <div class="fp-ab-mission-icon"><i class="bi bi-bullseye"></i></div>
                    <h4>Our Mission</h4>
                    <p>To democratize access to quality products by providing flexible, transparent, and affordable installment payment solutions that empower Nigerians to shop without limits.</p>
                </div>
                <div class="col-lg-6">
                    <div class="fp-ab-mission-icon"><i class="bi bi-eye"></i></div>
                    <h4>Our Vision</h4>
                    <p>To become the most trusted and innovative installment payment platform in Africa, transforming the way millions of people shop and manage their finances.</p>
                </div>
            </div>
        </div>

        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-gem"></i> Our Values</div>
            <h2>What We Stand For</h2>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-4 reveal-up" style="transition-delay:0.05s;">
                <div class="fp-ab-value-card">
                    <div class="fp-ab-value-icon is-trust"><i class="bi bi-shield-fill-check"></i></div>
                    <h5>Customer Trust</h5>
                    <span class="fp-ab-value-tagline">Transparency First</span>
                    <p>Always transparent about terms and fees, building lasting relationships through honesty and integrity.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 reveal-up" style="transition-delay:0.12s;">
                <div class="fp-ab-value-card">
                    <div class="fp-ab-value-icon is-flex"><i class="bi bi-arrow-repeat"></i></div>
                    <h5>Flexibility</h5>
                    <span class="fp-ab-value-tagline">Adapt & Thrive</span>
                    <p>Find the right plan that fits your budget — weekly, bi-weekly, or monthly. You choose what works.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 reveal-up" style="transition-delay:0.19s;">
                <div class="fp-ab-value-card">
                    <div class="fp-ab-value-icon is-speed"><i class="bi bi-lightning-fill"></i></div>
                    <h5>Speed</h5>
                    <span class="fp-ab-value-tagline">Instant Action</span>
                    <p>Process requests with instant approval mindset — no delays, no unnecessary waiting.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 reveal-up" style="transition-delay:0.26s;">
                <div class="fp-ab-value-card">
                    <div class="fp-ab-value-icon is-reliable"><i class="bi bi-patch-check-fill"></i></div>
                    <h5>Reliability</h5>
                    <span class="fp-ab-value-tagline">Promise Kept</span>
                    <p>Follow through on every delivery promise. We don't just commit — we deliver.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 reveal-up" style="transition-delay:0.33s;">
                <div class="fp-ab-value-card">
                    <div class="fp-ab-value-icon is-innovate"><i class="bi bi-gear-fill"></i></div>
                    <h5>Innovation</h5>
                    <span class="fp-ab-value-tagline">Always Evolving</span>
                    <p>Suggest improvements, take initiative, and constantly find better ways to serve our customers.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 reveal-up" style="transition-delay:0.40s;">
                <div class="fp-ab-value-card">
                    <div class="fp-ab-value-icon is-team"><i class="bi bi-people-fill"></i></div>
                    <h5>Teamwork</h5>
                    <span class="fp-ab-value-tagline">Together We Win</span>
                    <p>Share knowledge, support teammates, and grow together to create exceptional experiences.</p>
                </div>
            </div>
        </div>

        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-clock-history"></i> Our Journey</div>
            <h2>How We Started</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="fp-ab-timeline reveal-up">
                    <div class="fp-ab-tl-item">
                        <div class="fp-ab-tl-dot"></div>
                        <div class="fp-ab-tl-year">January 2025</div>
                        <div class="fp-ab-tl-text">OwnPace Store was founded with a vision to make quality products accessible to all Nigerians through flexible installment payments.</div>
                    </div>
                    <div class="fp-ab-tl-item">
                        <div class="fp-ab-tl-dot"></div>
                        <div class="fp-ab-tl-year">March 2025</div>
                        <div class="fp-ab-tl-text">Launched our beta platform with 500+ products across electronics, fashion, and home appliances categories.</div>
                    </div>
                    <div class="fp-ab-tl-item">
                        <div class="fp-ab-tl-dot"></div>
                        <div class="fp-ab-tl-year">June 2025</div>
                        <div class="fp-ab-tl-text">Reached 5,000 registered users and expanded our product catalog to 2,000+ items from top brands.</div>
                    </div>
                    <div class="fp-ab-tl-item">
                        <div class="fp-ab-tl-dot"></div>
                        <div class="fp-ab-tl-year">Today</div>
                        <div class="fp-ab-tl-text">Serving thousands of happy customers with 5,000+ products, 36+ payment plans, and nationwide delivery across all 36 states.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="padding-bottom:80px;">
    <div class="container">
        <div class="fp-ab-cta reveal-up">
            <h3>Ready to Start Shopping?</h3>
            <p>Join thousands of Nigerians who shop flexibly with OwnPace Store</p>
            <a href="{{ url('/shop') }}" class="btn-primary-gold" style="display:inline-flex;"><i class="bi bi-grid-fill"></i> Browse Products</a>
        </div>
    </div>
</section>

@include('frontend.partials.footer')
@endsection
