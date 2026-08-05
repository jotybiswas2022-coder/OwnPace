<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Every tracked click on a campaign email link. One row per click so the
 * metrics view can answer "which links did recipients click, and when".
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_link_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_log_id')->constrained('campaign_logs')->cascadeOnDelete();
            $table->string('url', 1000);
            $table->timestamps();

            $table->index(['campaign_log_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_link_clicks');
    }
};
