@extends('backend.layouts.console')
@section('title', 'Order #'.$order->id.' — '.storeName().' Admin')
@section('page_title', 'Order #'.$order->id)

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Orders', 'route' => 'admin.orders.index'], ['label' => '#'.$order->id]]])
@endsection

@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    {{-- Left: order details + items --}}
    <div class="space-y-6 lg:col-span-2">
        <div class="os-card overflow-hidden">
            <div class="border-b border-ink/10 px-6 py-4">
                <h3 class="font-display text-sm font-bold text-ink">Order Details</h3>
            </div>
            <div class="grid gap-5 p-6 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <p class="os-label">Order Date</p>
                    <p class="text-sm font-semibold text-ink">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="os-label">Customer</p>
                    <p class="text-sm font-semibold text-ink">{{ $order->user?->name ?? 'N/A' }}</p>
                    <p class="text-xs text-slate">{{ $order->user?->email }}</p>
                </div>
                <div>
                    <p class="os-label">Grand Total</p>
                    <p class="font-mono text-xl font-bold text-mango-ink">{{ formatPrice($order->grand_total, 0) }}</p>
                </div>
                <div>
                    <p class="os-label">Base Amount</p>
                    <p class="text-sm font-semibold text-ink">{{ formatPrice($order->base_amount, 0) }}</p>
                </div>
                <div>
                    <p class="os-label">Shipping</p>
                    <p class="text-sm font-semibold text-ink">{{ formatPrice($order->shipping_fee, 0) }}</p>
                </div>
                <div>
                    <p class="os-label">Interest</p>
                    <p class="text-sm font-semibold text-ink">{{ formatPrice($order->interest_amount, 0) }}</p>
                </div>
                <div>
                    <p class="os-label">Insurance</p>
                    <p class="text-sm font-semibold text-ink">{{ $order->has_insurance ? formatPrice($order->insurance_fee, 0) : 'None' }}</p>
                </div>
                <div>
                    <p class="os-label">Payment Plan</p>
                    <p class="text-sm font-semibold text-ink">{{ $order->installmentPlan?->duration ?? 'N/A' }} {{ $order->installmentPlan?->duration_unit ?? '' }}</p>
                </div>
                <div>
                    <p class="os-label">Paid Amount</p>
                    <p class="text-sm font-semibold text-grass">{{ formatPrice($order->paid_amount ?? 0, 0) }}</p>
                </div>
            </div>
        </div>

        <div class="os-card overflow-hidden">
            <div class="border-b border-ink/10 px-6 py-4">
                <h3 class="font-display text-sm font-bold text-ink">Order Items</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="os-table w-full">
                    <thead>
                        <tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td data-label="Product">
                                <div class="flex items-center gap-3">
                                    @if($item->product && $item->product->primaryImage)
                                        <img src="{{ imageUrl($item->product->primaryImage->image_path) }}" alt="{{ $item->product->name }}" class="h-10 w-10 flex-shrink-0 rounded-lg border border-ink/10 object-cover">
                                    @else
                                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-ink/5 text-slate"><i class="bi bi-image"></i></span>
                                    @endif
                                    <span class="font-semibold text-ink">{{ $item->product?->name ?? 'Product' }}</span>
                                </div>
                            </td>
                            <td data-label="Price" class="font-mono text-sm text-slate">{{ formatPrice($item->unit_price, 0) }}</td>
                            <td data-label="Qty" class="text-slate">{{ $item->quantity }}</td>
                            <td data-label="Total" class="font-mono font-semibold text-ink">{{ formatPrice($item->subtotal ?? ($item->quantity * $item->unit_price), 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="border-t border-ink/10 text-right font-bold text-ink">Subtotal</td>
                            <td class="border-t border-ink/10 font-mono font-bold text-mango-ink">{{ formatPrice($order->total_amount, 0) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="border-t border-ink/10 text-right font-bold text-ink">Grand Total</td>
                            <td class="border-t border-ink/10 font-mono font-bold text-mango-ink">{{ formatPrice($order->grand_total, 0) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Right: status controls + installments --}}
    <div class="space-y-6">
        <div class="os-card p-6">
            <h3 class="font-display text-sm font-bold text-ink">Order Status</h3>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="mt-4 space-y-3">
                @csrf
                <label for="status" class="os-label">Status</label>
                <select name="status" id="status" class="os-input w-full">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="os-btn os-btn-brand w-full"><i class="bi bi-check-lg"></i> Update Status</button>
            </form>
        </div>

        <div class="os-card p-6">
            <h3 class="font-display text-sm font-bold text-ink">Delivery Status</h3>
            <form action="{{ route('admin.orders.delivery', $order) }}" method="POST" class="mt-4 space-y-3">
                @csrf
                <label for="delivery_status" class="os-label">Status</label>
                <select name="delivery_status" id="delivery_status" class="os-input w-full">
                    <option value="pending" {{ $order->delivery_status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="eligible" {{ $order->delivery_status == 'eligible' ? 'selected' : '' }}>Eligible for Shipping</option>
                    <option value="processing" {{ $order->delivery_status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->delivery_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="in_transit" {{ $order->delivery_status == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="out_for_delivery" {{ $order->delivery_status == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                    <option value="delivered" {{ $order->delivery_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="failed" {{ $order->delivery_status == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                <button type="submit" class="os-btn os-btn-brand w-full"><i class="bi bi-truck"></i> Update Delivery</button>
            </form>
            <div class="mt-4 border-t border-ink/10 pt-4">
                <p class="os-label">Delivery Proxy</p>
                @if($order->deliveryProxyUser)
                    <p class="text-sm font-semibold text-ink">{{ $order->deliveryProxyUser->name }}</p>
                    <p class="text-xs text-slate">{{ $order->deliveryProxyUser->phone ?? $order->deliveryProxyUser->email }}</p>
                @else
                    <p class="text-sm text-slate">None</p>
                @endif
            </div>
        </div>

        <div class="os-card overflow-hidden">
            <div class="border-b border-ink/10 px-6 py-4">
                <h3 class="font-display text-sm font-bold text-ink">Installment Payments</h3>
            </div>
            <div class="divide-y divide-ink/5 px-6">
                @forelse($order->installmentPayments ?? [] as $ip)
                <div class="flex items-center justify-between gap-3 py-3">
                    <div>
                        <p class="text-sm font-semibold text-ink">#{{ $ip->installment_number }}</p>
                        <p class="text-xs text-slate">Due: {{ $ip->due_date->format('M d') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-mono text-sm font-semibold text-mango-ink">{{ formatPrice($ip->amount, 0) }}</p>
                        <span class="os-chip {{ $ip->status == 'paid' ? 'os-chip-grass' : 'os-chip-mango' }}" style="font-size:10px;">{{ ucfirst($ip->status) }}</span>
                    </div>
                </div>
                @empty
                <p class="py-4 text-sm text-slate">No installment records</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
