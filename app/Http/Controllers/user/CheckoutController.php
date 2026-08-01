<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\InstallmentPlan;
use App\Models\InstallmentPayment;
use App\Models\PaymentTransaction;
use App\Models\Wallet;
use App\Models\DeliveryAddress;
use App\Models\Setting;
use App\Models\InsuranceSetting;
use App\Models\PromoCode;
use App\Services\InstallmentCalculatorService;
use App\Services\InstallmentScheduleService;
use App\Services\Payments\PaymentGatewayManager;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'Your cart is empty.');
        }

        $installmentPlans = InstallmentPlan::where('is_active', true)->orderBy('sort_order')->get();
        $addresses = auth()->user()->deliveryAddresses;
        $settings = Setting::first();
        $insurance = InsuranceSetting::first();
        $wallet = auth()->user()->wallet;

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $shippingFee = \App\Models\ProductFee::where('slug', 'delivery_fee')->first()?->amount ?? 0;

        $promoCode = null;
        $discount = 0;
        $promoSession = session('promo_code');
        if ($promoSession) {
            $promoCode = PromoCode::where('code', $promoSession['code'])->first();
            if ($promoCode && $promoCode->isValid() && $total >= $promoCode->min_order_amount) {
                $discount = $promoCode->calculateDiscount($total);
            } else {
                session()->forget('promo_code');
            }
        }

        return view('frontend.checkout', compact(
            'cart', 'total', 'installmentPlans', 'shippingFee',
            'addresses', 'settings', 'insurance', 'wallet',
            'promoCode', 'discount'
        ));
    }

    public function process(Request $request)
    {
        $request->validate([
            'payment_type' => 'required|in:full,installment',
            // exclude_unless drops the field for full payments — the plan select
            // is hidden (display:none) but still submits "0", which must not
            // fail min:1 on a pay-in-full order.
            'installment_plan_id' => 'required_if:payment_type,installment|exclude_unless:payment_type,installment|integer|min:1|exists:installment_plans,id',
            'delivery_address_id' => 'required|exists:delivery_addresses,id',
            'has_insurance' => 'boolean',
            'payment_method' => 'required|in:wallet,paystack,flutterwave,korapay',
            'agree_terms' => 'required|accepted',
            'delivery_proxy_user_id' => 'nullable|integer|min:1|exists:users,id',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $baseAmount = $totalAmount;
        $shippingFee = \App\Models\ProductFee::where('slug', 'delivery_fee')->first()?->amount ?? 0;
        $insurance = InsuranceSetting::first();

        // Apply promo code discount first so every downstream number is right.
        $discountAmount = 0;
        $promoCodeId = null;
        $promoSession = session('promo_code');
        if ($promoSession) {
            $promoCode = PromoCode::where('code', $promoSession['code'])->first();
            if ($promoCode && $promoCode->isValid() && $totalAmount >= $promoCode->min_order_amount) {
                $discountAmount = $promoCode->calculateDiscount($totalAmount);
                $promoCodeId = $promoCode->id;
                $promoCode->increment('used_count');
            }
            session()->forget('promo_code');
        }

        $insuranceRate = $request->has_insurance && $insurance?->is_enabled
            ? (float) $insurance->rate
            : null;

        $installmentPlan = null;
        if ($request->payment_type === 'installment') {
            $installmentPlan = InstallmentPlan::findOrFail($request->installment_plan_id);
        }

        // Single source of truth for the money math — the tested service.
        $breakdown = $installmentPlan
            ? InstallmentCalculatorService::breakdown($totalAmount, $installmentPlan, $shippingFee, $insuranceRate, $discountAmount)
            : InstallmentCalculatorService::payOnceBreakdown($totalAmount, $shippingFee, $insuranceRate, $discountAmount);

        $interestAmount = $breakdown['interest'];
        $insuranceFee = $breakdown['insurance_fee'];
        $grandTotal = $breakdown['grand_total'];

        // Create order
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'user_id' => auth()->id(),
            'installment_plan_id' => $installmentPlan?->id,
            'status' => $request->payment_type === 'installment' ? 'pending' : 'processing',
            'total_amount' => $totalAmount,
            'base_amount' => $baseAmount,
            'shipping_fee' => $shippingFee,
            'insurance_fee' => $insuranceFee,
            'interest_amount' => $interestAmount,
            'grand_total' => $grandTotal,
            'discount_amount' => $discountAmount,
            'promo_code_id' => $promoCodeId,
            'paid_amount' => 0,
            'remaining_amount' => $grandTotal,
            'payment_type' => $request->payment_type,
            'has_insurance' => $request->has_insurance ?? false,
            'delivery_address_id' => $request->delivery_address_id,
            'delivery_proxy_user_id' => $request->delivery_proxy_user_id,
        ]);

        // Create order items
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'unit_price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        // Create installment payment schedule if installment — exact sums via the service.
        if ($request->payment_type === 'installment' && $installmentPlan) {
            InstallmentScheduleService::createSchedule($order, $installmentPlan);
        }

        // Clear cart
        session()->forget('cart');

        // Process payment
        if ($request->payment_method === 'wallet') {
            return $this->processWalletPayment($order);
        }

        // Fast fintech checkout — the amount due NOW is the first installment
        // (down payment) for installment orders, or the full total for Pay Once.
        // The chosen gateway is initialized immediately, no separate step.
        $targetInstallment = $request->payment_type === 'installment' && $installmentPlan
            ? $order->installmentPayments()->orderBy('installment_number')->first()
            : null;

        $dueNow = $targetInstallment
            ? (float) $targetInstallment->amount
            : (float) $order->grand_total;

        $transaction = PaymentTransaction::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'amount' => $dueNow,
            'installment_payment_id' => $targetInstallment?->id,
            'transaction_reference' => 'TXN-' . strtoupper(Str::random(15)),
            'gateway' => $request->payment_method,
            'status' => 'pending',
        ]);

        try {
            $result = app(PaymentGatewayManager::class)
                ->driver($request->payment_method)
                ->initialize($transaction);

            if ($result['success'] && $result['url']) {
                return redirect($result['url']);
            }
        } catch (\Throwable $e) {
            // Gateway unavailable — log it and fall through to the gateway page.
            report($e);
        }

        // Fallback: let the customer pick a gateway on the payment page.
        session(['pending_payment' => [
            'label' => $targetInstallment ? 'First installment (down payment)' : 'Order total',
            'amount' => $dueNow,
            'late_fee' => 0,
            'installment_payment_id' => $targetInstallment?->id,
        ]]);

        return redirect()->route('payment.gateway', $order->id)
            ->with('info', 'Choose a payment method below to complete your order.');
    }

    private function processWalletPayment(Order $order)
    {
        $wallet = auth()->user()->wallet;

        // Amount due now: the first unpaid installment for installment orders,
        // the full total for Pay Once — never the whole balance on an installment.
        $target = InstallmentScheduleService::nextUnpaid($order);
        $dueNow = $order->payment_type === 'installment' && $target
            ? (float) $target->amount
            : (float) $order->grand_total;

        if (!$wallet || (float) $wallet->balance < $dueNow) {
            // Keep the amount due-now consistent — stage it so the gateway page
            // doesn't fall back to the full remaining balance on an installment.
            session(['pending_payment' => [
                'label' => $target ? 'First installment (down payment)' : 'Order total',
                'amount' => $dueNow,
                'late_fee' => 0,
                'installment_payment_id' => $target?->id,
            ]]);

            return redirect()->route('payment.gateway', $order->id)
                ->with('error', 'Insufficient wallet balance.');
        }

        $balanceBefore = $wallet->balance;
        $wallet->decrement('balance', $dueNow);

        \App\Models\WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => auth()->id(),
            'amount' => $dueNow,
            'balance_before' => $balanceBefore,
            'balance_after' => $wallet->balance,
            'type' => 'payment',
            'description' => 'Down payment for order #' . $order->order_number,
            'status' => 'completed',
        ]);

        // Mark the first unpaid installment paid, or close out for Pay Once.
        InstallmentScheduleService::recordPayment(
            $order,
            $dueNow,
            'wallet',
            $order->payment_type === 'installment' ? $target : null
        );

        return redirect()->route('order.confirmation', $order->id)
            ->with('success', 'Payment successful via Wallet!');
    }

    /**
     * Search registered store users by phone/email/name to assign as the
     * delivery proxy. Never returns the current user.
     */
    public function searchProxy(Request $request)
    {
        $request->validate(['q' => 'required|string|max:100']);

        $q = trim($request->q);
        if (mb_strlen($q) < 3) {
            return response()->json(['users' => []]);
        }

        $users = \App\Models\User::where('id', '!=', auth()->id())
            ->where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            })
            ->limit(8)
            ->get(['id', 'name', 'email', 'phone']);

        return response()->json(['users' => $users]);
    }

    public function paymentGateway(Order $order)
    {
        return view('frontend.payment.gateway', compact('order'));
    }

    public function processPayment(Request $request, Order $order)
    {
        $request->validate([
            'gateway' => 'required|in:paystack,flutterwave,korapay',
        ]);

        // The exact amount was staged by OrderController (next installment /
        // partial / full). Capture it BEFORE clearing so a revisit can't show
        // stale numbers and so we never charge the full balance by mistake.
        $pending = session('pending_payment');

        // Due-now semantics: the staged amount wins; otherwise an installment
        // order is due its next installment (+ late fee), never the whole
        // remaining balance; pay-once orders fall back to the full balance.
        $dueFallback = $order->payment_type === 'installment'
            ? InstallmentScheduleService::nextDueAmount($order)
            : 0;

        $amount = $pending['amount']
            ?? ($dueFallback > 0 ? $dueFallback : $order->remaining_amount)
            ?? $order->grand_total;

        $installmentPaymentId = $pending['installment_payment_id'] ?? null;
        session()->forget('pending_payment');

        $transaction = PaymentTransaction::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'amount' => $amount,
            'installment_payment_id' => $installmentPaymentId,
            'transaction_reference' => 'TXN-' . strtoupper(Str::random(15)),
            'gateway' => $request->gateway,
            'status' => 'pending',
        ]);

        // Hand off to the gateway adapter — one interface, any provider.
        try {
            $result = app(PaymentGatewayManager::class)
                ->driver($request->gateway)
                ->initialize($transaction);
        } catch (\Throwable $e) {
            $result = ['success' => false, 'url' => null, 'message' => 'Payment could not be started. Please try again.'];
        }

        if ($result['success'] && $result['url']) {
            return redirect($result['url']);
        }

        return redirect()->route('order.confirmation', $order->id)
            ->with('error', $result['message'] ?? 'Payment could not be initialized. Please try again.');
    }

    public function applyPromoCode(Request $request)
    {
        $request->validate(['code' => 'required|string|max:50']);

        $promoCode = PromoCode::where('code', $request->code)->first();

        if (!$promoCode || !$promoCode->isValid()) {
            return back()->with('error', 'Invalid or expired promo code.');
        }

        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        if ($total < $promoCode->min_order_amount) {
            return back()->with('error', 'Minimum order amount of ₦' . number_format($promoCode->min_order_amount, 0) . ' required.');
        }

        session(['promo_code' => ['code' => $promoCode->code, 'discount' => $promoCode->calculateDiscount($total)]]);

        return back()->with('success', 'Promo code "' . $promoCode->code . '" applied!');
    }

    public function removePromoCode()
    {
        session()->forget('promo_code');
        return back()->with('success', 'Promo code removed.');
    }

    public function confirmation(Order $order)
    {
        $order->load(['items.product', 'installmentPlan', 'installmentPayments', 'deliveryAddress', 'promoCode']);
        return view('frontend.order.confirmation', compact('order'));
    }
}
