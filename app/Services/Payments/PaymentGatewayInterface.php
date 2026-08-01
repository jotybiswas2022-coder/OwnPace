<?php

namespace App\Services\Payments;

use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

/**
 * PaymentGatewayInterface — the shared contract every payment gateway adapter
 * implements. Adding a new gateway (or removing one) is a single class plus a
 * single registration line, nothing else in the app changes.
 *
 * Adapters own the provider-specific HTTP calls; the rest of the app talks
 * only to this contract (via PaymentGatewayManager).
 */
interface PaymentGatewayInterface
{
    /**
     * Provider key used in forms/transactions, e.g. 'paystack'.
     */
    public function name(): string;

    /**
     * Start a charge for a pending transaction.
     *
     * @return array{success: bool, url: ?string, message: ?string}
     */
    public function initialize(PaymentTransaction $transaction): array;

    /**
     * Verify a transaction after a callback / webhook reports success.
     *
     * @param  array<string, mixed>  $payload  Raw gateway response data.
     * @return array{success: bool, data: array<string, mixed>}
     */
    public function verify(PaymentTransaction $transaction, array $payload): array;

    /**
     * Parse an incoming webhook request into a uniform shape.
     *
     * @return array{reference: ?string, success: bool, data: array<string, mixed>}
     */
    public function parseWebhook(Request $request): array;
}
