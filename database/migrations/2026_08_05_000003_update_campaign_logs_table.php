<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Campaign delivery metrics live on each recipient log row:
 *   - email / phone: contact snapshots so metrics survive account edits
 *   - provider_message_id: gateway message id for delivery-status lookups
 *   - opened_at / open_count: email open tracking (pixel)
 *   - click_count: total tracked link clicks
 *   - error: last failure reason for failed sends
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_logs', function (Blueprint $table) {
            $table->string('email')->nullable()->after('channel');
            $table->string('phone')->nullable()->after('email');
            $table->string('provider_message_id')->nullable()->after('phone');
            $table->timestamp('opened_at')->nullable()->after('sent_at');
            $table->unsignedInteger('open_count')->default(0)->after('opened_at');
            $table->unsignedInteger('click_count')->default(0)->after('open_count');
            $table->string('error')->nullable()->after('click_count');
        });
    }

    public function down(): void
    {
        Schema::table('campaign_logs', function (Blueprint $table) {
            $table->dropColumn([
                'email', 'phone', 'provider_message_id',
                'opened_at', 'open_count', 'click_count', 'error',
            ]);
        });
    }
};
