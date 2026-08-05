<?php

namespace App\Services;

use App\Models\DeliveryTracking;
use App\Models\Order;
use App\Models\Setting;

/**
 * DeliveryStatusService — owns the delivery milestone rule: once a customer
 * has paid the configured share of the order (default 70%), the order becomes
 * eligible for shipment. The delivery fee is already folded into the order
 * total at checkout, so this never adds a charge — it only unlocks shipping.
 */
class DeliveryStatusService
{
    /**
     * Threshold percentage from settings (default 70).
     */
    public static function thresholdPercent(): float
    {
        return (float) (Setting::first()?->delivery_threshold_percentage ?? 70);
    }

    /**
     * Evaluate an order after a payment and flip delivery_status to
     * 'eligible' (with a timeline event) the first time the threshold is met.
     */
    public static function evaluate(Order $order): void
    {
        // Only the pending → eligible transition logs a timeline event. Already
        // past the gate (eligible/shipped/…) must not duplicate it on later payments.
        if ($order->delivery_status !== 'pending') {
            return;
        }

        $threshold = self::thresholdPercent();
        $paidPercent = MoneyService::percentOf($order->paid_amount, $order->grand_total);

        if ($paidPercent < $threshold) {
            return;
        }

        $order->update(['delivery_status' => 'eligible']);

        DeliveryTracking::create([
            'order_id' => $order->id,
            'status' => 'eligible',
            'description' => 'Eligible for shipping — ' . MoneyService::plain($paidPercent) . '% paid',
            'tracked_at' => now(),
        ]);
    }

    /**
     * Record an admin delivery status change + a timeline event.
     */
    public static function transition(Order $order, string $newStatus): void
    {
        $previous = $order->delivery_status;
        $order->update(['delivery_status' => $newStatus]);

        if ($newStatus === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        DeliveryTracking::create([
            'order_id' => $order->id,
            'status' => $newStatus,
            'description' => 'Delivery status updated to ' . ucwords(str_replace('_', ' ', $newStatus)),
            'tracked_at' => now(),
        ]);

        // Automated notifications on delivery milestones (queued).
        if ($previous === $newStatus || ! $order->user) {
            return;
        }

        if ($newStatus === 'delivered') {
            $order->user->notify(new \App\Notifications\DeliveryConfirmationNotification($order->fresh()));
        } elseif (in_array($newStatus, ['processing', 'shipped', 'in_transit', 'out_for_delivery'], true)) {
            $order->user->notify(new \App\Notifications\OrderStatusNotification($order->fresh(), $newStatus));
        }
    }
}
