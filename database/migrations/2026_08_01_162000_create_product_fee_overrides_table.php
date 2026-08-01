<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-product overrides on top of the global product_fees rows. A row here
 * overrides the global fee of the same slug for that specific product.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_fee_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('fee_slug'); // matches product_fees.slug
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('type')->default('fixed'); // fixed, percentage
            $table->timestamps();

            $table->unique(['product_id', 'fee_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_fee_overrides');
    }
};
