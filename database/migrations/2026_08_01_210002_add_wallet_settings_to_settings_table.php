<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Wallet rules configured by the admin:
     *  - allow_topup_withdrawal: whether self-funded top-ups are withdrawable
     *    (client confirmed: admin setting, default OFF — spend-only).
     *  - withdrawal_fee_percent: flat % deducted on withdrawal (default 10).
     *  - topup_bonus_percent: bonus store credit added on every top-up.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('allow_topup_withdrawal')->default(false)->after('guest_checkout');
            $table->decimal('withdrawal_fee_percent', 5, 2)->default(10)->after('allow_topup_withdrawal');
            $table->decimal('topup_bonus_percent', 5, 2)->default(0)->after('withdrawal_fee_percent');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['allow_topup_withdrawal', 'withdrawal_fee_percent', 'topup_bonus_percent']);
        });
    }
};
