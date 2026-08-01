<?php

namespace App\Services\Payments\Concerns;

use App\Models\Setting;

/**
 * Shared config lookup for gateway adapters. Keys are read from the admin
 * Settings screen (stored as JSON) with .env fallbacks, so a gateway works
 * out of the box in development with env keys and in production via admin.
 */
trait ReadsGatewayConfig
{
    /**
     * Resolve secret/public keys for a gateway from settings or env.
     *
     * @param  array<string, string>  $fallbacks  env key per config key
     * @return array<string, string>
     */
    protected function gatewayConfig(array $fallbacks): array
    {
        $saved = Setting::first()?->gateway_config ?? [];

        $resolved = [];
        foreach ($fallbacks as $key => $env) {
            $resolved[$key] = $saved[$key] ?? env($env) ?? '';
        }

        return $resolved;
    }
}
