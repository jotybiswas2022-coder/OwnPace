<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Dedupe ledger for automated (system-triggered) notifications.
 *
 * The daily reminder commands must not re-notify the same customer about the
 * same installment day after day, so every automated dispatch records a row
 * here with a unique (entity_type, entity_id, type) pair. Event-driven
 * notifications (order status, delivery) do not need this.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');                 // payment_due | payment_overdue
            $table->string('entity_type')->nullable(); // e.g. App\Models\InstallmentPayment
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('channels')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->unique(['type', 'entity_type', 'entity_id'], 'notification_logs_dedupe');
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
