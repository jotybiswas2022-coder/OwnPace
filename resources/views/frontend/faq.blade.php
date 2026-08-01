@extends('frontend.app')
@section('title', 'FAQs — OwnPace Store')

@push('styles')
<style>
.fp-faq-hero {
    position: relative; padding: 50px 0 30px; overflow: hidden; isolation: isolate;
    background: linear-gradient(180deg, rgba(234,179,8,0.03) 0%, transparent 100%);
}
.fp-faq-orb {
    position: absolute; width: 500px; height: 500px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.05) 0%, transparent 60%);
    top: -200px; right: -100px; pointer-events: none;
    animation: fqOrbPulse 4s ease-in-out infinite;
}
.fp-faq-orb2 {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.03) 0%, transparent 60%);
    bottom: -150px; left: -100px; pointer-events: none;
    animation: fqOrbPulse 5s ease-in-out infinite reverse;
}
@keyframes fqOrbPulse { 0%,100%{transform:scale(1);opacity:0.5} 50%{transform:scale(1.1);opacity:1} }

.fp-faq-search {
    position: relative; max-width: 500px; margin: 0 auto 32px;
}
.fp-faq-search input {
    width: 100%; padding: 14px 18px 14px 46px;
    background: var(--card-dark); border: 1.5px solid var(--card-border);
    border-radius: var(--radius-sm); color: var(--text-primary);
    font-family: inherit; font-size: 14px; outline: none;
    transition: all 0.3s;
}
.fp-faq-search input:focus { border-color: var(--gold-500); box-shadow: 0 0 0 3px rgba(234,179,8,0.08); }
.fp-faq-search input::placeholder { color: var(--text-dim); }
.fp-faq-search i {
    position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
    color: var(--text-dim); font-size: 16px;
}

.fp-faq-pills {
    display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; margin-bottom: 36px;
}
.fp-faq-pill {
    padding: 8px 18px; border-radius: 99px;
    background: var(--card-dark); border: 1px solid var(--card-border);
    color: var(--text-muted); font-size: 13px; font-weight: 500; cursor: pointer;
    transition: all 0.3s; display: flex; align-items: center; gap: 6px;
    font-family: inherit; touch-action: manipulation;
}
.fp-faq-pill:hover { border-color: rgba(234,179,8,0.3); color: var(--gold-400); }
.fp-faq-pill.active { background: rgba(234,179,8,0.1); border-color: var(--gold-500); color: var(--gold-400); }
.fp-faq-pill i { font-size: 14px; }

.fp-faq-section { padding-bottom: 80px; }

.fp-faq-category { margin-bottom: 32px; }
.fp-faq-cat-title {
    font-family: 'Syne', sans-serif;
    font-size: 17px; font-weight: 700;
    color: var(--gold-400);
    margin-bottom: 16px;
    display: flex; align-items: center; gap: 8px;
}

.fp-accordion .accordion-item {
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-sm) !important;
    margin-bottom: 8px;
    overflow: hidden;
    transition: all 0.3s;
}
.fp-accordion .accordion-item:hover {
    border-color: rgba(234,179,8,0.2);
    transform: translateX(4px);
}

.fp-accordion .accordion-button {
    background: var(--card-dark);
    color: var(--text-primary);
    font-weight: 600;
    font-size: 14px;
    padding: 16px 20px;
    box-shadow: none;
    font-family: 'Space Grotesk', sans-serif;
    transition: all 0.3s;
}
.fp-accordion .accordion-button i {
    color: var(--gold-500);
    margin-right: 10px;
    font-size: 16px;
    flex-shrink: 0;
}
.fp-accordion .accordion-button:not(.collapsed) {
    background: rgba(234,179,8,0.05);
    color: var(--gold-400);
}
.fp-accordion .accordion-button::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23A1A1AA'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 01.708 0L8 10.293l5.646-5.647a.5.5 0 01.708.708l-6 6a.5.5 0 01-.708 0l-6-6a.5.5 0 010-.708z'/%3e%3c/svg%3e");
}
.fp-accordion .accordion-button:not(.collapsed)::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23EAB308'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 01.708 0L8 10.293l5.646-5.647a.5.5 0 01.708.708l-6 6a.5.5 0 01-.708 0l-6-6a.5.5 0 010-.708z'/%3e%3c/svg%3e");
}
.fp-accordion .accordion-body {
    color: var(--text-muted);
    font-size: 14px;
    line-height: 1.7;
    padding: 0 20px 20px;
}

.fp-faq-not-found {
    text-align: center; padding: 40px 20px; display: none;
}
.fp-faq-not-found i { font-size: 48px; color: var(--card-border); display: block; margin-bottom: 16px; }
.fp-faq-not-found h4 { font-family: 'Syne', sans-serif; color: var(--text-primary); margin-bottom: 6px; }
.fp-faq-not-found p { color: var(--text-dim); font-size: 14px; }

@media (max-width: 768px) {
    .fp-faq-pills { gap: 6px; }
    .fp-faq-pill { font-size: 12px; padding: 6px 14px; }
}
</style>
@endpush

@section('content')
<section class="fp-faq-hero">
    <div class="fp-faq-orb"></div>
    <div class="fp-faq-orb2"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-question-circle-fill"></i> FAQs</div>
            <h2>Frequently Asked Questions</h2>
            <p>Everything you need to know about shopping with OwnPace</p>
        </div>

        <div class="fp-faq-search reveal-up">
            <i class="bi bi-search"></i>
            <input type="text" id="faqSearch" placeholder="Search FAQs..." oninput="filterFAQs(this.value)">
        </div>

        <div class="fp-faq-pills reveal-up" id="faqPills">
            <button class="fp-faq-pill active" data-cat="all" onclick="filterCategory(this, 'all')"><i class="bi bi-grid-fill"></i> All</button>
            <button class="fp-faq-pill" data-cat="payments" onclick="filterCategory(this, 'payments')"><i class="bi bi-coin"></i> Payments &amp; Plans</button>
            <button class="fp-faq-pill" data-cat="delivery" onclick="filterCategory(this, 'delivery')"><i class="bi bi-truck"></i> Delivery &amp; Shipping</button>
            <button class="fp-faq-pill" data-cat="insurance" onclick="filterCategory(this, 'insurance')"><i class="bi bi-shield-check"></i> Insurance &amp; Returns</button>
        </div>
    </div>
</section>

<section class="fp-faq-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(isset($faqs) && $faqs->count() > 0)
                    @foreach($faqs as $category => $faqGroup)
                    <div class="fp-faq-category reveal-up" data-category="{{ Str::slug($category) }}">
                        <h3 class="fp-faq-cat-title">{{ $category }}</h3>
                        <div class="accordion fp-accordion" id="faqAccordion{{ $loop->index }}">
                            @foreach($faqGroup as $faq)
                            <div class="accordion-item faq-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}">
                                        <i class="bi bi-question-circle"></i>
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="faq{{ $faq->id }}" class="accordion-collapse collapse"
                                     data-bs-parent="#faqAccordion{{ $loop->index }}">
                                    <div class="accordion-body">
                                        {{ $faq->answer }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="fp-faq-category reveal-up" data-category="payments">
                    <h3 class="fp-faq-cat-title"><i class="bi bi-coin"></i> Payments &amp; Plans</h3>
                    <div class="accordion fp-accordion" id="faqPayments">
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    <i class="bi bi-question-circle"></i> How does OwnPace installment work?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqPayments">
                                <div class="accordion-body">OwnPace allows you to purchase products and pay over time. Choose from weekly (4–40 weeks) or monthly (1–12 months) plans. Pay 70% upfront to get your item shipped, then complete the remaining balance in installments.</div>
                            </div>
                        </div>
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    <i class="bi bi-question-circle"></i> What payment methods do you accept?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqPayments">
                                <div class="accordion-body">We accept credit/debit cards, bank transfers, USSD, and wallet payments. We integrate with Paystack, Flutterwave, and Korapay for secure transactions.</div>
                            </div>
                        </div>
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    <i class="bi bi-question-circle"></i> Can I pay off my plan early?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqPayments">
                                <div class="accordion-body">Yes! You can pay your next installment before the due date, pay any specific amount of your choice, or pay off the entire balance at once with no early payment penalty.</div>
                            </div>
                        </div>
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    <i class="bi bi-question-circle"></i> Can I change my installment plan?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqPayments">
                                <div class="accordion-body">Absolutely! You can request to change your installment type/duration. Simply go to your orders page, request a plan change with a reason, and our admin team will review and approve it.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fp-faq-category reveal-up" data-category="delivery" style="transition-delay:0.1s;">
                    <h3 class="fp-faq-cat-title"><i class="bi bi-truck"></i> Delivery &amp; Shipping</h3>
                    <div class="accordion fp-accordion" id="faqDelivery">
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    <i class="bi bi-question-circle"></i> When will my item be delivered?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqDelivery">
                                <div class="accordion-body">Your item will be shipped once you've paid at least 70% of the total order. Delivery times vary by location, typically 3–7 business days within major cities.</div>
                            </div>
                        </div>
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                    <i class="bi bi-question-circle"></i> Can I track my delivery?
                                </button>
                            </h2>
                            <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqDelivery">
                                <div class="accordion-body">Yes! You can view your delivery timeline and tracking information from your orders page. We'll also send you notifications when your item is ready to ship.</div>
                            </div>
                        </div>
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
                                    <i class="bi bi-question-circle"></i> Can someone else receive my delivery?
                                </button>
                            </h2>
                            <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqDelivery">
                                <div class="accordion-body">Yes, you can assign a proxy (a registered store user) to receive your delivery if you're unavailable. You can manage this in your delivery settings.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fp-faq-category reveal-up" data-category="insurance" style="transition-delay:0.2s;">
                    <h3 class="fp-faq-cat-title"><i class="bi bi-shield-check"></i> Insurance &amp; Returns</h3>
                    <div class="accordion fp-accordion" id="faqInsurance">
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">
                                    <i class="bi bi-question-circle"></i> Can I insure my product?
                                </button>
                            </h2>
                            <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqInsurance">
                                <div class="accordion-body">Yes! You can add insurance to any product for 10% of the total order. This covers your product against damage, loss, or theft during the installment period.</div>
                            </div>
                        </div>
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq9">
                                    <i class="bi bi-question-circle"></i> Can I cancel my installment plan?
                                </button>
                            </h2>
                            <div id="faq9" class="accordion-collapse collapse" data-bs-parent="#faqInsurance">
                                <div class="accordion-body">Yes, you can request to cancel your installment plan. A 10% cancellation charge applies. The remaining amount will be refunded to your wallet for future purchases.</div>
                            </div>
                        </div>
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq10">
                                    <i class="bi bi-question-circle"></i> Can I exchange my product?
                                </button>
                            </h2>
                            <div id="faq10" class="accordion-collapse collapse" data-bs-parent="#faqInsurance">
                                <div class="accordion-body">Yes! You can request to exchange your product for one from your wishlist. Submit an exchange request with your reason, and our admin team will review and approve it.</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="fp-faq-not-found" id="faqNotFound">
                    <i class="bi bi-search-heart"></i>
                    <h4>No results found</h4>
                    <p>Try a different search term or browse by category above</p>
                </div>

                <div class="text-center mt-5 reveal-up">
                    <div style="background:var(--card-dark);border:1px solid var(--card-border);border-radius:var(--radius);padding:32px;max-width:500px;margin:0 auto;">
                        <i class="bi bi-headset" style="font-size:36px;color:var(--gold-500);display:block;margin-bottom:12px;"></i>
                        <h4 style="font-family:'Syne',sans-serif;color:var(--text-primary);margin-bottom:6px;">Still have questions?</h4>
                        <p style="color:var(--text-muted);font-size:14px;margin-bottom:20px;">Our support team is ready to help you</p>
                        <a href="{{ url('/contact') }}" class="btn-primary-gold" style="display:inline-flex;"><i class="bi bi-chat-dots-fill"></i> Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.partials.footer')

<script>
function filterFAQs(query) {
    const items = document.querySelectorAll('.faq-item');
    let visible = 0;
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(query.toLowerCase())) {
            item.style.display = '';
            visible++;
        } else {
            item.style.display = 'none';
        }
    });
    document.getElementById('faqNotFound').style.display = visible === 0 ? 'block' : 'none';
}

function filterCategory(btn, cat) {
    document.querySelectorAll('.fp-faq-pill').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.fp-faq-category').forEach(catEl => {
        const catAttr = catEl.dataset.category;
        if (cat === 'all' || catAttr === cat) {
            catEl.style.display = '';
        } else {
            catEl.style.display = 'none';
        }
    });
    document.getElementById('faqNotFound').style.display = 'none';
    document.getElementById('faqSearch').value = '';
    document.querySelectorAll('.faq-item').forEach(i => i.style.display = '');
}
</script>
@endsection
