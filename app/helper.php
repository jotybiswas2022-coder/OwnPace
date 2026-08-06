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

/**
 * Resolve an image path for display. Accepts either a storage-relative path
 * (storage/...) or an absolute URL (e.g. a remote image imported via CSV).
 */
function imageUrl($path)
{
    if (!$path) {
        return null;
    }

    if (preg_match('/^https?:\/\//i', $path)) {
        return $path;
    }

    return asset('storage/' . $path);
}

/**
 * Serve a file from the Laravel public dir through the framework.
 *
 * This deployment boots Laravel from a custom docroot (XAMPP htdocs root),
 * so Vite's /build/* bundles and the public storage symlink are not reachable
 * as static files. This helper streams them via a scoped route while keeping
 * the file inside the intended directories (path-traversal safe).
 */
function servePublicFile($relative)
{
    $relative = str_replace('\\', '/', ltrim((string) $relative, '/'));

    // Reject empty, traversal and null-byte payloads.
    if ($relative === '' || str_contains($relative, "\0") || preg_match('#(^|/)\.\.(/|$)#', $relative)) {
        abort(404);
    }

    $full = realpath(public_path($relative));
    if (! $full || ! is_file($full)) {
        abort(404);
    }

    // Allow files inside the public dir itself, or inside the public storage
    // symlink target (core/storage/app/public).
    $allowedBases = array_filter([realpath(public_path()), realpath(public_path('storage'))]);
    $within = false;
    foreach ($allowedBases as $base) {
        if ($base && str_starts_with($full, $base . DIRECTORY_SEPARATOR)) {
            $within = true;
            break;
        }
    }
    if (! $within) {
        abort(404);
    }

    // Symfony's file mime guesser is unreliable on Windows — map by extension.
    $mimes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'mjs' => 'application/javascript',
        'map' => 'application/json',
        'json' => 'application/json',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'avif' => 'image/avif',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'otf' => 'font/otf',
        'eot' => 'application/vnd.ms-fontobject',
        'pdf' => 'application/pdf',
        'mp4' => 'video/mp4',
        'webm' => 'video/webm',
        'txt' => 'text/plain',
    ];
    $ext = strtolower(pathinfo($full, PATHINFO_EXTENSION));

    return response()->file($full, [
        'Content-Type' => $mimes[$ext] ?? 'application/octet-stream',
        'Cache-Control' => 'public, max-age=86400',
    ]);
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

/**
 * Attach the per-product card data the storefront needs: how many plans a
 * product offers and the lowest per-payment installment ("from ₦X/wk").
 * Shared by the home page and the shop catalog so every card shows the
 * same numbers. Expects products loaded with the `installmentPlans` relation.
 */
function attachInstallmentData($products)
{
    return $products->each(function ($product) {
        $product->installment_plans_count = $product->installmentPlans->count();

        $lowest = $product->installmentPlans
            ->where('is_active', true)
            ->sortBy('duration')
            ->first();

        if ($lowest) {
            $breakdown = InstallmentCalculatorService::breakdown((float) $product->price, $lowest);
            $product->installment_from = $breakdown['per_installment'];
            $product->installment_type = $lowest->type;
        } else {
            $product->installment_from = null;
            $product->installment_type = null;
        }
    });
}

/**
 * Product progress badge for an order card — one of In Progress /
 * Completed / Canceled, shown across the account area.
 */
function orderProgressBadge($order)
{
    $status = $order->status;

    if ($status === 'completed') {
        return ['label' => 'Completed', 'class' => 'completed', 'icon' => 'bi-check-circle-fill'];
    }

    if ($status === 'cancelled') {
        return ['label' => 'Canceled', 'class' => 'cancelled', 'icon' => 'bi-x-circle-fill'];
    }

    return ['label' => 'In Progress', 'class' => 'in-progress', 'icon' => 'bi-arrow-repeat'];
}
