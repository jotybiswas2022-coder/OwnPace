<?php

use App\Models\Setting;
use App\Models\ProductFee;
use App\Services\MoneyService;
use App\Services\InstallmentCalculatorService;

/**
 * Store display name. The default is "OwnPace" — the product thesis is
 * gradual ownership ("Own at your own pace"). The setting in the DB wins.
 */
function storeName()
{
    return Setting::first()?->store_name ?? 'OwnPace';
}

/**
 * Currency symbol (default ₦ for the Nigerian market).
 */
function currency()
{
    return MoneyService::symbol();
}

/**
 * Format a price for display, e.g. ₦1,250,000.
 *
 * Defaults to 2 decimals for legacy pages; new pages pass 0 explicitly.
 */
function formatPrice($amount, $decimals = 2)
{
    return MoneyService::format($amount, $decimals);
}

function cartCount()
{
    $cart = session()->get('cart', []);
    return count($cart);
}

function getFee($slug)
{
    $fee = ProductFee::where('slug', $slug)->first();
    if (!$fee) return 0;
    return $fee->type === 'percentage' ? $fee->amount : $fee->amount;
}

function getDeliveryThreshold()
{
    $settings = Setting::first();
    return $settings?->delivery_threshold_percentage ?? 70;
}

function getDiscountPercentage($order = null)
{
    return 0;
}

function orderStatusBadge($status)
{
    $colors = [
        'pending' => 'warning',
        'processing' => 'info',
        'partial_paid' => 'primary',
        'completed' => 'success',
        'cancelled' => 'danger',
    ];
    $color = $colors[$status] ?? 'secondary';
    return "<span class=\"badge bg-{$color}\">{$status}</span>";
}

function deliveryStatusBadge($status)
{
    $colors = [
        'pending' => 'secondary',
        'processing' => 'info',
        'shipped' => 'primary',
        'in_transit' => 'warning',
        'out_for_delivery' => 'info',
        'delivered' => 'success',
        'failed' => 'danger',
    ];
    $color = $colors[$status] ?? 'secondary';
    return "<span class=\"badge bg-{$color}\">{$status}</span>";
}

function verificationBadge($status)
{
    $colors = [
        'verified' => 'success',
        'pending' => 'warning',
        'rejected' => 'danger',
        'unverified' => 'secondary',
    ];
    $color = $colors[$status] ?? 'secondary';
    return "<span class=\"badge bg-{$color}\">{$status}</span>";
}

/**
 * Installment breakdown for a plan — delegates to the tested service class.
 */
function calculateInstallmentBreakdown($totalAmount, $installmentPlan)
{
    return InstallmentCalculatorService::breakdown($totalAmount, $installmentPlan);
}
