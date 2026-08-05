<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-type channel toggles for automated notifications.
 *
 * Stored as a JSON object keyed by notification type:
 *   {
 *     "payment_due":         ["mail", "sms", "database"],
 *     "payment_overdue":     ["mail", "sms", "database"],
 *     "order_status":        ["mail", "sms", "database"],
 *     "delivery_confirmation": ["mail", "sms", "database"]
 *   }
 * A type missing from the map falls back to all channels (see
 * App\Services\Messaging\NotificationChannels).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->json('notification_channels')->nullable()->after('sms_settings');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('notification_channels');
        });
    }
};
