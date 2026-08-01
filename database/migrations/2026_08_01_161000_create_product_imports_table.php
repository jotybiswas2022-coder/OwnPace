<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tracks a CSV product-import run so a queued job can report per-row
 * success/error results back to the admin without holding a session.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('file_name');
            $table->integer('total_rows')->default(0);
            $table->integer('success_rows')->default(0);
            $table->integer('error_rows')->default(0);
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->json('report')->nullable(); // per-row validation report
            $table->text('error')->nullable();   // fatal error message
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_imports');
    }
};
