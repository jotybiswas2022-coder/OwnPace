<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Optional late fee on installment plans — off by default, per plan.
     */
    public function up(): void
    {
        Schema::table('installment_plans', function (Blueprint $table) {
            $table->boolean('late_fee_enabled')->default(false)->after('interest_rate');
            $table->decimal('late_fee_percent', 5, 2)->default(0)->after('late_fee_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('installment_plans', function (Blueprint $table) {
            $table->dropColumn(['late_fee_enabled', 'late_fee_percent']);
        });
    }
};
