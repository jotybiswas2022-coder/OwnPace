<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Every wallet transaction carries a `withdrawable` flag: whether that
     * money can be moved back to a bank account (subject to the withdrawal
     * fee). Cancellation refunds are withdrawable; top-ups are not by default
     * (admin setting allow_topup_withdrawal flips the rule for new deposits).
     */
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->boolean('withdrawable')->default(false)->after('type');
            $table->index('withdrawable');
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropIndex(['withdrawable']);
            $table->dropColumn('withdrawable');
        });
    }
};
