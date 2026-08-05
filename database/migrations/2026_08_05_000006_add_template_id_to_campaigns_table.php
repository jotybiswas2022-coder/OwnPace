<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Campaigns can start from a reusable template — the FK is nullable and
 * preserved as provenance on the campaign after the send.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->foreignId('template_id')
                ->nullable()
                ->after('recipient_filters')
                ->constrained('campaign_templates')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropConstrainedForeignId('template_id');
        });
    }
};
