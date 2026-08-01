<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A customer's request to move withdrawable wallet funds to a bank
     * account. The funds are HELD (debited) at request time; an admin reviews
     * the request and moves it pending → processing → completed/failed.
     *
     * fee = withdrawal_fee_percent (default 10%) of amount.
     * net_amount = amount − fee, the portion actually sent to the bank.
     */
    public function up(): void
    {
        Schema::create('wallet_withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bank_account_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 15, 2);
            $table->decimal('fee', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2)->default(0);
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->text('admin_note')->nullable();
            $table->string('reference')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_withdrawal_requests');
    }
};
