<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reusable broadcast templates. Campaigns can start from a template
 * (pre-filled content) or be saved as a new template while composing.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject')->nullable();
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_templates');
    }
};
