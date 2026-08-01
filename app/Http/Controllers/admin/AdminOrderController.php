<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\OrderStatusRequest;
use App\Http\Requests\Admin\DeliveryStatusRequest;
use App\Http\Requests\Admin\FeeRequest;
use App\Models\Order;
use App\Models\InstallmentPlan;
use App\Models\ProductFee;
use App\Models\ProductFeeOverride;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'installmentPlan', 'items.product']);

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->payment_type) {
            $query->where('payment_type', $request->payment_type);
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);
        $installmentPlans = InstallmentPlan::where('is_active', true)->get();

        return view('backend.orders.index', compact('orders', 'installmentPlans'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'installmentPlan', 'items.product', 'installmentPayments', 'transactions', 'deliveryAddress', 'deliveryTrackings', 'deliveryProxyUser']);
        return view('backend.orders.show', compact('order'));
    }

    public function updateStatus(OrderStatusRequest $request, Order $order)
    {
        $this->authorize('manage', $order);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated to ' . $request->status);
    }

    public function updateDeliveryStatus(DeliveryStatusRequest $request, Order $order)
    {
        $this->authorize('manage', $order);

        // Records the status change + a timeline event for the customer page.
        \App\Services\DeliveryStatusService::transition($order, $request->delivery_status);

        return back()->with('success', 'Delivery status updated!');
    }

    public function fees()
    {
        $fees = ProductFee::all();
        $overrides = ProductFeeOverride::with('product')->latest()->get();
        $products = Product::where('status', 'active')->orderBy('name')->get();
        return view('backend.orders.fees', compact('fees', 'overrides', 'products'));
    }

    public function updateFee(FeeRequest $request, ProductFee $fee)
    {
        $this->authorize('manage', $fee);

        $fee->update([
            'amount' => $request->amount,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Fee updated successfully!');
    }

    /**
     * Save (upsert) a per-product fee override.
     */
    public function storeFeeOverride(Request $request)
    {
        $this->authorize('manage', ProductFee::class);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'fee_slug' => 'required|string|exists:product_fees,slug',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:fixed,percentage',
        ]);

        ProductFeeOverride::updateOrCreate(
            ['product_id' => $request->product_id, 'fee_slug' => $request->fee_slug],
            ['amount' => $request->amount, 'type' => $request->type]
        );

        return back()->with('success', 'Per-product fee override saved!');
    }

    public function destroyFeeOverride(ProductFeeOverride $override)
    {
        $this->authorize('manage', ProductFee::class);

        $override->delete();
        return back()->with('success', 'Override removed — product falls back to the global fee.');
    }

    public function export()
    {
        $orders = Order::with(['user', 'installmentPlan'])->latest()->get();
        // Return CSV export
        $csv = "Order #,Customer,Amount,Status,Payment Type,Created\n";
        foreach ($orders as $order) {
            $csv .= "{$order->order_number},{$order->user->name},{$order->grand_total},{$order->status},{$order->payment_type},{$order->created_at}\n";
        }
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders_export.csv"',
        ]);
    }
}
