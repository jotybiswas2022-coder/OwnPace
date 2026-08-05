<?php

namespace App\Services\Messaging;

use App\Models\Setting;

/**
 * NotificationChannels — per-type channel toggles for automated notifications.
 *
 * The settings row carries a JSON map (notification_channels) of
 *   type => ['mail', 'sms', 'database']
 * which admins edit on the Secure Config screen. Types absent from the map
 * default to all three channels, so the feature works before any toggle is
 * touched.
 */
class NotificationChannels
{
    /** Every notification type this app can emit. */
    public const TYPES = [
        'payment_due' => 'Upcoming payment due',
        'payment_overdue' => 'Missed / overdue payment',
        'order_status' => 'Order status change',
        'delivery_confirmation' => 'Delivery confirmation',
    ];

    /** Every channel a type can be sent over. */
    public const CHANNELS = ['mail', 'sms', 'database'];

    /**
     * The channels enabled for a notification type.
     *
     * Types missing from the map fall back to every channel. A type present
     * with an empty list is intentionally disabled (admins can switch a type
     * off entirely from the Secure Config screen).
     *
     * @return array<int, string>
     */
    public static function for(string $type): array
    {
        $map = Setting::first()?->notification_channels;

        if (! is_array($map) || ! array_key_exists($type, $map)) {
            return self::CHANNELS;
        }

        return array_values(array_filter((array) $map[$type], fn ($c) => in_array($c, self::CHANNELS)));
    }
}
