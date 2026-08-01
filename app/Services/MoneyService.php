<?php

namespace App\Services;

use App\Models\Setting;

/**
 * MoneyService — the single source of truth for every monetary value in the
 * app. Currency formatting, rounding and parsing all live here (never inline
 * in controllers). Money bugs are the most expensive kind, so this is kept
 * deliberately small and covered by tests.
 */
class MoneyService
{
    /**
     * Resolve the store's currency symbol from settings (default: Naira ₦).
     */
    public static function symbol(): string
    {
        return Setting::first()?->currency_symbol ?? '₦';
    }

    /**
     * Format an amount for display, e.g. ₦1,250,000.
     */
    public static function format(float|int|string|null $amount, int $decimals = 0): string
    {
        return self::symbol().number_format((float) $amount, $decimals);
    }

    /**
     * Format without the symbol (used inside tables/cards that already show it).
     */
    public static function plain(float|int|string|null $amount, int $decimals = 0): string
    {
        return number_format((float) $amount, $decimals);
    }

    /**
     * Round monetary values to the nearest kobo/kopeck — never truncate.
     */
    public static function round(float|int|string|null $amount): float
    {
        return round((float) $amount, 2);
    }

    /**
     * Parse a human string ("₦1,250,000", "1 250 000", "1250000") into a float.
     * Strips symbols, spaces and thousands separators.
     */
    public static function parse(float|int|string $amount): float
    {
        $clean = preg_replace('/[^\d.-]/', '', (string) $amount);

        return (float) $clean;
    }

    /**
     * Percentage of $part relative to $whole, clamped 0-100.
     */
    public static function percentOf(float|int|string $part, float|int|string $whole): float
    {
        $whole = (float) $whole;

        if ($whole <= 0) {
            return 0;
        }

        return max(0, min(100, ((float) $part / $whole) * 100));
    }
}
