<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\InstallmentPlan;
use App\Models\InstallmentPayment;
use App\Models\PlanChangeRequest;
use App\Models\ExchangeRequest;
use App\Models\PaymentTransaction;
use App\Models\Review;
use App\Services\InstallmentCalculatorService;
use App\Services\InstallmentScheduleService;
use App\Services\MoneyService;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Order history grouped by lifecycle: active plans (still being paid
     * off), completed, cancelled. Saved payment methods ride along so the
     * page doubles as the "manage your money" hub.
     */
    public function index(Request $request)
    {
        $query = auth()->user()->orders()->with(['installmentPlan', 'items.product']);

        $activeStatuses = ['pending', 'processing', 'partial_paid', 'shipped'];
        $tab = $request->input('tab', 'all');

        switch ($tab) {
            case 'active':
                $query->whereIn('status', $activeStatuses);
                break;
            case 'completed':
                $query->where('status', 'completed');
                break;
            case 'cancelled':
                $query->where('status', 'cancelled');
                break;
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        $savedCards = auth()->user()->savedCards;
        $bankAccounts = auth()->user()->bankAccounts;
        $counts = [
            'all' => auth()->user()->orders()->count(),
            'active' => auth()->user()->orders()->whereIn('status', $activeStatuses)->count(),
            'completed' => auth()->user()->orders()->where('status', 'completed')->count(),
            'cancelled' => auth()->user()->orders()->where('status', 'cancelled')->count(),
        ];

        return view('frontend.order.index', compact('orders', 'savedCards', 'bankAccounts', 'counts'))
            ->with('tab', $tab);
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        $order->load([
            'items.product',
            'installmentPlan',
            'installmentPayments',
            'transactions',
            'deliveryAddress',
            'deliveryTrackings',
            'deliveryProxyUser',
        ]);

        $nextPayment = InstallmentScheduleService::nextUnpaid($order);
        $nextLateFee = $nextPayment ? InstallmentScheduleService::lateFeeFor($nextPayment) : 0.0;

        $deliveryReviewDone = Review::where('user_id', auth()->id())
            ->where('order_id', $order->id)
            ->where('reviewable_type', 'delivery')
            ->exists();

        return view('frontend.order.show', compact('order'))->with([
            'nextPayment' => $nextPayment,
            'nextDue' => InstallmentScheduleService::nextDueAmount($order),
            'nextLateFee' => $nextLateFee,
            'progressPct' => InstallmentCalculatorService::progressPercent($order->paid_amount, $order->grand_total),
            'progressLabel' => InstallmentScheduleService::progressLabel($order),
            'walletBalance' => (float) (auth()->user()->wallet?->balance ?? 0),
            'deliveryReviewDone' => $deliveryReviewDone,
        ]);
    }

    /**
     * One-time post-delivery review: rate the delivery person and satisfaction
     * with the product. The reviews table's unique constraint keeps it to once.
     */
    public function submitReview(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        if ($order->delivery_status !== 'delivered') {
            return back()->with('error', 'You can only review after delivery.');
        }

        $request->validate([
            'delivery_rating' => 'required|integer|min:1|max:5',
            'delivery_comment' => 'nullable|string|max:1000',
            'product_rating' => 'nullable|integer|min:1|max:5',
            'product_comment' => 'nullable|string|max:1000',
        ]);

        // Delivery person rating — reviewable_id is the order (no delivery
        // entity exists); the unique constraint blocks a second submission.
        Review::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'reviewable_type' => 'delivery',
                'reviewable_id' => $order->id,
            ],
            [
                'rating' => $request->delivery_rating,
                'comment' => $request->delivery_comment,
            ]
        );

        // Product satisfaction — rated on the first item, once per product.
        $product = $order->items->first()?->product;
        if ($product && $request->filled('product_rating')) {
            Review::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'reviewable_type' => 'product',
                    'reviewable_id' => $product->id,
                ],
                [
                    'rating' => $request->product_rating,
                    'comment' => $request->product_comment,
                ]
            );
        }

        return back()->with('success', 'Thanks for your feedback!');
    }

    /**
     * Pay the next unpaid installment (or a specific one) early, via gateway.
     * Optional late fee is added when the payment is overdue and the plan
     * enables it. Creates a pending transaction and hands off to the gateway.
     */
    public function payInstallment(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        $payment = $request->filled('installment_payment_id')
            ? $order->installmentPayments()->findOrFail($request->installment_payment_id)
            : InstallmentScheduleService::nextUnpaid($order);

        if (!$payment) {
            return back()->with('error', 'This order has no unpaid installments.');
        }

        if ($payment->status === 'paid') {
            return back()->with('error', 'This installment is already paid.');
        }

        $lateFee = InstallmentScheduleService::lateFeeFor($payment);
        $amount = MoneyService::round($payment->amount + $lateFee);

        $this->createPaymentTransaction($order, $amount, 'payment', $payment);

        session(['pending_payment' => [
            'label' => 'Installment #' . $payment->installment_number . ' of ' . $order->installmentPlan?->duration,
            'amount' => $amount,
            'late_fee' => $lateFee,
            'installment_payment_id' => $payment->id,
        ]]);

        return redirect()->route('payment.gateway', $order->id)
            ->with('info', 'Continue to pay installment #' . $payment->installment_number . ' of ₦' . number_format($amount, 2) . '.');
    }

    /**
     * Pay any custom partial amount (min ₦100) via gateway.
     */
    public function payPartial(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        if ($request->amount > $order->remaining_amount) {
            return back()->with('error', 'Amount exceeds remaining balance.');
        }

        $amount = MoneyService::round($request->amount);

        $this->createPaymentTransaction($order, $amount, 'payment');

        session(['pending_payment' => [
            'label' => 'Partial payment',
            'amount' => $amount,
            'late_fee' => 0,
        ]]);

        return redirect()->route('payment.gateway', $order->id)
            ->with('info', 'Partial payment of ₦' . number_format($amount, 2) . ' initiated.');
    }

    /**
     * Pay off the entire remaining balance in one go, via gateway.
     */
    public function payFull(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        $amount = (float) $order->remaining_amount;

        if ($amount <= 0) {
            return back()->with('error', 'This order is already fully paid.');
        }

        $this->createPaymentTransaction($order, $amount, 'payment');

        session(['pending_payment' => [
            'label' => 'Full balance',
            'amount' => $amount,
            'late_fee' => 0,
        ]]);

        return redirect()->route('payment.gateway', $order->id)
            ->with('info', 'Full payment of ₦' . number_format($amount, 2) . ' initiated.');
    }

    /**
     * Pay from the wallet — fully functional, no external gateway. The amount
     * clears the next unpaid installment(s) and the schedule is recalculated.
     */
    public function payWallet(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        $amount = MoneyService::round($request->amount ?? InstallmentScheduleService::nextDueAmount($order));

        if ($amount <= 0) {
            return back()->with('error', 'There is nothing left to pay on this order.');
        }

        if ($amount > (float) $order->remaining_amount) {
            return back()->with('error', 'Amount exceeds remaining balance.');
        }

        $wallet = auth()->user()->wallet;
        if (!$wallet || (float) $wallet->balance < $amount) {
            return back()->with('error', 'Insufficient wallet balance.');
        }

        // All wallet money moves through the tested service so the ledger
        // invariants live in exactly one place.
        \App\Services\WalletService::debit(
            $wallet,
            $amount,
            'payment',
            'Payment on order #' . $order->order_number
        );

        $this->createPaymentTransaction($order, $amount, 'payment', null, 'wallet', 'success');

        // Advance the schedule: the next unpaid installment is marked paid; if
        // the amount clears the whole balance, recalculate closes out the rest.
        InstallmentScheduleService::recordPayment(
            $order,
            $amount,
            'wallet',
            InstallmentScheduleService::nextUnpaid($order)
        );

        return back()->with('success', 'Payment of ₦' . number_format($amount, 2) . ' received via wallet.');
    }

    /**
     * Shared transaction creation for order payments.
     */
    private function createPaymentTransaction(
        Order $order,
        float $amount,
        string $type = 'payment',
        ?InstallmentPayment $installment = null,
        string $gateway = 'paystack',
        string $status = 'pending'
    ): PaymentTransaction {
        return PaymentTransaction::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'installment_payment_id' => $installment?->id,
            'transaction_reference' => strtoupper($type) . '-' . Str::random(12),
            'gateway' => $gateway,
            'amount' => $amount,
            'currency' => 'NGN',
            'status' => $status,
            'type' => $type,
        ]);
    }

    /**
     * Pick a new plan for an active order — the dedicated page reached from
     * the "Change Plan" action on an order. The POST (requestPlanChange)
     * keeps the request pending until an admin approves or rejects it.
     */
    public function changePlanForm(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        if (!in_array($order->status, ['pending', 'processing', 'partial_paid'])) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'You can only change the plan on an active order.');
        }

        if (!$order->installmentPlan) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'This order is not on an installment plan.');
        }

        $order->load(['installmentPlan', 'items.product']);
        $plans = InstallmentPlan::where('is_active', true)
            ->where('id', '!=', $order->installment_plan_id)
            ->orderBy('sort_order')->get();

        // A previously submitted pending request blocks a duplicate.
        $pendingRequest = PlanChangeRequest::where('order_id', $order->id)
            ->where('status', 'pending')->first();

        return view('frontend.order.change-plan', compact('order', 'plans', 'pendingRequest'));
    }

    public function requestPlanChange(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        if (!in_array($order->status, ['pending', 'processing', 'partial_paid'])) {
            return back()->with('error', 'You can only change the plan on an active order.');
        }

        $request->validate([
            'requested_plan_id' => 'required|exists:installment_plans,id',
            'reason' => 'required|string|min:10',
        ]);

        if (PlanChangeRequest::where('order_id', $order->id)->where('status', 'pending')->exists()) {
            return back()->with('error', 'You already have a pending plan change request for this order.');
        }

        PlanChangeRequest::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'current_plan_id' => $order->installment_plan_id,
            'requested_plan_id' => $request->requested_plan_id,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('requests.index')
            ->with('success', 'Plan change request submitted. An admin will review it.');
    }

    /**
     * Exchange request flow: pick a wishlist product to swap the ordered one
     * for, plus a reason. Stays pending until admin approval.
     */
    public function exchangeForm(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        if (!in_array($order->status, ['pending', 'processing', 'partial_paid'])) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'You can only request an exchange on an active order.');
        }

        $order->load(['installmentPlan', 'items.product']);

        $wishlist = auth()->user()->wishlist()->with('product')->get()
            ->map(fn($item) => $item->product)
            ->filter();

        $currentProduct = $order->items()->first()?->product;
        $pendingRequest = ExchangeRequest::where('order_id', $order->id)
            ->where('status', 'pending')->first();

        return view('frontend.order.exchange', compact('order', 'wishlist', 'currentProduct', 'pendingRequest'));
    }

    public function requestExchange(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        if (!in_array($order->status, ['pending', 'processing', 'partial_paid'])) {
            return back()->with('error', 'You can only request an exchange on an active order.');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'reason' => 'required|string|min:10',
        ]);

        $currentProductId = $order->items()->first()?->product_id;
        if (!$currentProductId) {
            return back()->with('error', 'We could not find the product on this order. Please contact support.');
        }

        if (ExchangeRequest::where('order_id', $order->id)->where('status', 'pending')->exists()) {
            return back()->with('error', 'You already have a pending exchange request for this order.');
        }

        ExchangeRequest::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'current_product_id' => $currentProductId,
            'requested_product_id' => $request->product_id,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('requests.index')
            ->with('success', 'Exchange request submitted for admin review.');
    }

    public function cancelOrder(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        if (!in_array($order->status, ['pending', 'processing', 'partial_paid'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        $order->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->reason,
            'cancellation_fee' => 0,
        ]);

        // Refund 100% of what they've paid into the wallet (withdrawable —
        // the 10%-fee withdrawal rule is defined for cancellation refunds).
        $refund = \App\Services\WalletService::refundForCancellation($order);

        if ($refund) {
            return back()->with('success',
                'Order cancelled. ₦' . number_format((float) $refund->amount, 2)
                . ' refunded to your wallet.'
            );
        }

        return back()->with('info', 'Order cancelled.');
    }

    public function tracking(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        $order->load(['deliveryTrackings' => function($q) {
            $q->latest('tracked_at');
        }]);

        return view('frontend.order.tracking', compact('order'));
    }
}
