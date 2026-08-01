<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Delivery proxies let a customer delegate delivery pickup/handling to
 * another person or agent (e.g. a colleague who can receive the parcel).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_proxies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone');
            $table->string('relationship')->nullable(); // colleague, family, agent
            $table->string('id_number')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_proxies');
    }
};
