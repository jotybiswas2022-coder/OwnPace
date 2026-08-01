@extends('frontend.app')
@section('title', 'Contact Us — OwnPace Store')

@push('styles')
<style>
.fp-contact-hero {
    position: relative; padding: 60px 0 80px; overflow: hidden; isolation: isolate;
    background: linear-gradient(180deg, rgba(234,179,8,0.03) 0%, transparent 100%);
}
.fp-contact-orb {
    position: absolute; width: 600px; height: 600px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.05) 0%, transparent 60%);
    top: -250px; right: -150px; pointer-events: none;
    animation: ctOrbPulse 4s ease-in-out infinite;
}
.fp-contact-orb2 {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.03) 0%, transparent 60%);
    bottom: -100px; left: -100px; pointer-events: none;
    animation: ctOrbPulse 5s ease-in-out infinite reverse;
}
@keyframes ctOrbPulse { 0%,100%{transform:scale(1);opacity:0.5} 50%{transform:scale(1.1);opacity:1} }

.fp-contact-title {
    font-family: 'Syne', sans-serif;
    font-size: clamp(32px, 4vw, 48px); font-weight: 800;
    color: var(--text-primary); margin-bottom: 16px;
}
.fp-contact-desc {
    color: var(--text-muted); font-size: 16px; line-height: 1.7;
    margin-bottom: 36px; max-width: 480px;
}

.fp-ci-grid {
    display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;
}
.fp-ci-card {
    display: flex; align-items: center; gap: 14px;
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius-sm); padding: 16px 18px;
    transition: all 0.3s; cursor: default;
}
.fp-ci-card:hover {
    border-color: rgba(234,179,8,0.25); transform: translateY(-2px);
    box-shadow: var(--shadow-card-hover);
}
.fp-ci-icon {
    width: 44px; height: 44px; border-radius: 10px;
    background: rgba(234,179,8,0.1);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold-500); font-size: 18px; flex-shrink: 0;
}
.fp-ci-card strong { display: block; font-size: 13px; color: var(--text-primary); margin-bottom: 2px; }
.fp-ci-card span { font-size: 14px; color: var(--text-muted); }

.fp-ci-social {
    display: flex; gap: 8px; margin-top: 20px;
}
.fp-ci-social a {
    width: 40px; height: 40px; border-radius: 10px;
    background: var(--card-dark); border: 1px solid var(--card-border);
    display: flex; align-items: center; justify-content: center;
    color: var(--text-muted); font-size: 16px;
    transition: all 0.3s; text-decoration: none;
    touch-action: manipulation;
}
.fp-ci-social a:hover { transform: translateY(-3px); color: white; }
.fp-ci-social a.s-fb:hover { background: #1877f2; border-color: #1877f2; }
.fp-ci-social a.s-tw:hover { background: #000; border-color: #333; }
.fp-ci-social a.s-ig:hover { background: linear-gradient(135deg,#f58529,#dd2a7b,#8134af); border-color: transparent; }
.fp-ci-social a.s-wa:hover { background: #25d366; border-color: #25d366; }

.fp-contact-form-card {
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius); padding: 36px 32px; position: relative; overflow: hidden;
    transition: all 0.3s; contain: layout style;
}
.fp-contact-form-card:hover { border-color: rgba(234,179,8,0.15); }
.fp-contact-form-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--gold-500), var(--gold-400), var(--gold-600));
}
.fp-contact-form-card h3 {
    font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 700;
    color: var(--text-primary); margin-bottom: 6px;
}
.fp-contact-form-card > p {
    color: var(--text-muted); font-size: 14px; margin-bottom: 24px;
}

.fp-alert-success {
    display: flex; align-items: center; gap: 8px;
    background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.2);
    color: #4ade80; padding: 14px 18px; border-radius: var(--radius-sm);
    font-weight: 500; font-size: 13px; margin-bottom: 20px;
    animation: ctFadeDown 0.4s ease-out;
}
@keyframes ctFadeDown { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }

.fp-form-group { margin-bottom: 6px; }
.fp-form-group label {
    display: flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px;
}
.fp-form-group label i { color: var(--gold-500); font-size: 13px; }
.fp-form-control {
    width: 100%; padding: 12px 16px;
    background: var(--surface-dark); border: 1.5px solid var(--card-border);
    border-radius: var(--radius-sm); color: var(--text-primary);
    font-family: 'Space Grotesk', sans-serif; font-size: 14px; outline: none;
    transition: all 0.25s;
}
.fp-form-control:focus { border-color: var(--gold-500); box-shadow: 0 0 0 3px rgba(234,179,8,0.08); }
.fp-form-control::placeholder { color: var(--text-dim); }
.fp-form-control option { background: var(--card-dark); color: var(--text-primary); }
.fp-textarea { resize: vertical; min-height: 120px; }

.fp-submit-btn {
    width: 100%; padding: 14px; border: none;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black); border-radius: var(--radius-sm);
    font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 700;
    cursor: pointer; display: flex; align-items: center;
    justify-content: center; gap: 8px;
    transition: all 0.3s; position: relative; overflow: hidden;
}
.fp-submit-btn::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, transparent 20%, rgba(255,255,255,0.15) 50%, transparent 80%);
    transform: translateX(-100%); transition: transform 0.6s;
}
.fp-submit-btn:hover::before { transform: translateX(100%); }
.fp-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(234,179,8,0.25); }
.fp-submit-btn:active { transform: translateY(0); }

.fp-contact-extra {
    margin-top: 60px; position: relative; z-index: 1;
}
.fp-ce-card {
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius); padding: 32px; text-align: center;
    transition: all 0.3s; contain: layout style;
}
.fp-ce-card:hover {
    border-color: rgba(234,179,8,0.2); transform: translateY(-4px);
    box-shadow: var(--shadow-card-hover);
}
.fp-ce-card i { font-size: 36px; color: var(--gold-500); margin-bottom: 16px; display: block; }
.fp-ce-card h5 { font-family: 'Syne', sans-serif; color: var(--text-primary); font-size: 16px; margin-bottom: 8px; }
.fp-ce-card p { color: var(--text-dim); font-size: 14px; margin-bottom: 16px; }

@media (max-width: 991px) {
    .fp-contact-hero { padding: 40px 0 50px; }
    .fp-contact-form-card { margin-top: 32px; padding: 28px 24px; }
    .fp-ci-grid { grid-template-columns: 1fr; }
}
@media (max-width: 576px) {
    .fp-contact-form-card { padding: 22px 18px; }
}
</style>
@endpush

@section('content')
<section class="fp-contact-hero">
    <div class="fp-contact-orb" aria-hidden="true"></div>
    <div class="fp-contact-orb2" aria-hidden="true"></div>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="reveal-left">
                    <div class="section-badge" style="text-align:left;display:inline-flex;"><i class="bi bi-headset"></i> Contact Us</div>
                    <h1 class="fp-contact-title">We're Here to Help</h1>
                    <p class="fp-contact-desc">Have questions about payments, delivery, or your account? Reach out to our team and we'll get back to you within 24 hours.</p>

                    <div class="fp-ci-grid">
                        <div class="fp-ci-card">
                            <div class="fp-ci-icon"><i class="bi bi-envelope-fill"></i></div>
                            <div>
                                <strong>Email</strong>
                                <span>support@ownpace.store</span>
                            </div>
                        </div>
                        <div class="fp-ci-card">
                            <div class="fp-ci-icon"><i class="bi bi-telephone-fill"></i></div>
                            <div>
                                <strong>Phone</strong>
                                <span>+234 800-OWNPACE</span>
                            </div>
                        </div>
                        <div class="fp-ci-card">
                            <div class="fp-ci-icon"><i class="bi bi-geo-alt-fill"></i></div>
                            <div>
                                <strong>Location</strong>
                                <span>Lagos, Nigeria</span>
                            </div>
                        </div>
                        <div class="fp-ci-card">
                            <div class="fp-ci-icon"><i class="bi bi-clock-fill"></i></div>
                            <div>
                                <strong>Working Hours</strong>
                                <span>Mon–Sat: 8AM – 6PM (WAT)</span>
                            </div>
                        </div>
                    </div>

                    <div class="fp-ci-social">
                        <a href="#" class="s-fb" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="s-tw" aria-label="X (Twitter)"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="s-ig" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="s-wa" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="fp-contact-form-card reveal-right">
                    <h3>Send Us a Message</h3>
                    <p>Fill in the form and we'll respond promptly</p>

                    @if(session('success'))
                        <div class="fp-alert-success">
                            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.send') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="fp-form-group">
                                    <label><i class="bi bi-person-fill"></i> Full Name</label>
                                    <input type="text" name="name" class="fp-form-control" placeholder="John Doe" required value="{{ auth()->user()->name ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fp-form-group">
                                    <label><i class="bi bi-envelope-fill"></i> Email</label>
                                    <input type="email" name="email" class="fp-form-control" placeholder="john@example.com" required value="{{ auth()->user()->email ?? '' }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="fp-form-group">
                                    <label><i class="bi bi-chat-dots-fill"></i> Subject</label>
                                    <select name="subject" class="fp-form-control" required>
                                        <option value="">Select a topic...</option>
                                        <option value="Payment">Payment Issue</option>
                                        <option value="Delivery">Delivery Question</option>
                                        <option value="Installment">Installment Plan</option>
                                        <option value="Account">Account Support</option>
                                        <option value="Product">Product Inquiry</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="fp-form-group">
                                    <label><i class="bi bi-pencil-fill"></i> Message</label>
                                    <textarea name="message" class="fp-form-control fp-textarea" rows="4" placeholder="Describe your issue or question..." required></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="fp-submit-btn">
                                    <i class="bi bi-send-fill"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="fp-contact-extra">
            <div class="row g-4">
                <div class="col-md-4 reveal-up" style="transition-delay:0.1s;">
                    <div class="fp-ce-card">
                        <i class="bi bi-question-circle-fill"></i>
                        <h5>Frequently Asked Questions</h5>
                        <p>Find quick answers to common questions about payments, delivery, and more.</p>
                        <a href="{{ url('/faq') }}" class="btn-outline-gold" style="display:inline-flex;">View FAQs</a>
                    </div>
                </div>
                <div class="col-md-4 reveal-up" style="transition-delay:0.2s;">
                    <div class="fp-ce-card">
                        <i class="bi bi-chat-square-text-fill"></i>
                        <h5>Live Chat</h5>
                        <p>Chat with our support team in real-time during business hours.</p>
                        <button class="btn-outline-gold" style="display:inline-flex;" onclick="alert('Live chat coming soon!')"><i class="bi bi-chat-dots-fill"></i> Start Chat</button>
                    </div>
                </div>
                <div class="col-md-4 reveal-up" style="transition-delay:0.3s;">
                    <div class="fp-ce-card">
                        <i class="bi bi-newspaper"></i>
                        <h5>Help Center</h5>
                        <p>Browse our knowledge base for guides, tutorials, and troubleshooting.</p>
                        <button class="btn-outline-gold" style="display:inline-flex;" onclick="alert('Help center coming soon!')"><i class="bi bi-book-fill"></i> Browse Guides</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.partials.footer')
@endsection
