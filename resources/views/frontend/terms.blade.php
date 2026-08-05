@extends('frontend.layouts.store')
@section('title', ($type == 'payment' ? 'Payment Terms' : ($type == 'delivery' ? 'Delivery Policy' : ($type == 'privacy' ? 'Privacy Policy' : 'Terms & Conditions'))).' — '.storeName())

@php
    $osLegalTabs = [
        ['label' => 'Terms & Conditions', 'url' => url('/terms'), 'active' => $type === 'general'],
        ['label' => 'Payment Terms', 'url' => url('/terms/payment'), 'active' => $type === 'payment'],
        ['label' => 'Delivery Policy', 'url' => url('/terms/delivery'), 'active' => $type === 'delivery'],
        ['label' => 'Privacy Policy', 'url' => url('/terms/privacy'), 'active' => $type === 'privacy'],
    ];
    $osTitles = [
        'general' => 'Terms & Conditions',
        'payment' => 'Payment Terms',
        'delivery' => 'Delivery Policy',
        'privacy' => 'Privacy Policy',
    ];
    $osFallback = [
        'general' => [
            ['1. Payment Plans', 'We offer flexible installment payment plans ranging from 4 to 40 weeks or 1 to 12 months. Interest rates vary by plan. By selecting a plan, you commit to making timely payments as agreed.'],
            ['2. Delivery', 'Products will be shipped once 70% of the total order is paid. Delivery fees are calculated and included in the initial payment. Delivery timelines vary by location.'],
            ['3. Cancellation', 'You may cancel your installment plan at any time. A 10% cancellation fee applies. Refunds are credited to your wallet for future purchases.'],
            ['4. Insurance', 'Insurance is optional and costs a small percentage of the total order. It covers damage, loss, or theft during the installment period.'],
            ['5. Account', 'You are responsible for maintaining the confidentiality of your account credentials. We reserve the right to suspend or terminate accounts for violation of these terms.'],
        ],
        'payment' => [
            ['1. Payment Methods', 'We accept debit/credit cards, bank transfers, USSD and wallet funding through our trusted gateways: Paystack, Flutterwave and Korapay.'],
            ['2. Installment Schedules', 'Weekly, bi-weekly and monthly schedules are available. Your schedule is generated at checkout and every due amount is shown on your order page before you pay.'],
            ['3. Late Payments', 'If an installment is missed, it is marked overdue and we may notify you by email and SMS. Continued non-payment may pause future orders until your balance is settled.'],
            ['4. Early Settlement', 'You can pay down your balance at any time — a partial amount, the next installment, or the full remaining balance. There is never an early-payment penalty.'],
            ['5. Refunds & Cancellations', 'A 10% cancellation charge applies when you cancel a plan. The remaining balance is refunded to your wallet as withdrawable credit.'],
        ],
        'delivery' => [
            ['1. Dispatch Threshold', 'Orders ship once at least 70% of the total has been paid. Delivery fees are displayed before you confirm your order.'],
            ['2. Delivery Windows', 'Typical delivery is 3–7 business days within major cities, and up to 14 days for remote locations. You will receive tracking updates along the way.'],
            ['3. Delivery Proxy', 'If you cannot receive the delivery yourself, you can assign a registered proxy to collect it on your behalf.'],
            ['4. Failed Delivery', 'If a delivery attempt fails, we will contact you to reschedule. Repeated failed attempts may incur a re-delivery fee.'],
            ['5. Damage on Arrival', 'Inspect your item on delivery. Report any damage within 48 hours with photo evidence so we can arrange a resolution.'],
        ],
        'privacy' => [
            ['1. Data We Collect', 'We collect the information you provide — name, contact details, delivery addresses — plus transaction history needed to run your account.'],
            ['2. How We Use It', 'Your data powers your orders, wallet, payment schedules and notifications. We never sell your personal information.'],
            ['3. Payments', 'Card details are processed by PCI-DSS compliant gateways. We never store full card numbers on our servers.'],
            ['4. Your Rights', 'You can request a copy of your data or ask us to delete your account and its data at any time from your profile settings.'],
            ['5. Contact', 'Questions about privacy? Email us and we will respond within 24 hours.'],
        ],
    ];
@endphp

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-4xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-file-earmark-text-fill"></i> Legal</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">{{ $osTitles[$type] ?? 'Terms & Conditions' }}</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate">Please read these terms carefully before using our services.</p>

        <nav class="os-tabs mt-6" aria-label="Legal documents">
            @foreach($osLegalTabs as $osTab)
                <a href="{{ $osTab['url'] }}" class="os-tab {{ $osTab['active'] ? 'os-tab-active' : '' }}" @if($osTab['active']) aria-current="page" @endif>
                    <i class="bi {{ $osTab['active'] ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i> {{ $osTab['label'] }}
                </a>
            @endforeach
        </nav>
    </div>
</section>

<section class="os-section">
    <div class="mx-auto max-w-4xl px-4 sm:px-6">
        <div class="os-card p-6 sm:p-10" x-reveal>
            @if(isset($terms) && $terms)
                <div class="os-prose">
                    {!! nl2br(e($terms->content)) !!}
                </div>
            @else
                <div class="os-prose">
                    <p>By using {{ storeName() }}, you agree to the following terms and conditions. Please read them carefully.</p>
                    @foreach(($osFallback[$type] ?? $osFallback['general']) as [$osHeading, $osBody])
                        <h3>{{ $osHeading }}</h3>
                        <p>{{ $osBody }}</p>
                    @endforeach
                </div>
            @endif

            <div class="os-hr"></div>
            <p class="text-xs text-slate">
                <i class="bi bi-clock-history"></i> Last updated: {{ \Carbon\Carbon::parse($terms->updated_at ?? now())->format('F j, Y') }}. Questions? <a href="{{ url('/contact') }}" class="font-semibold text-brand underline underline-offset-2">Contact us</a>.
            </p>
        </div>
    </div>
</section>

@endsection
