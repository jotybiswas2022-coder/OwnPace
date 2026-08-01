<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Late fee actually charged (if any) on a paid installment.
     */
    public function up(): void
    {
        Schema::table('installment_payments', function (Blueprint $table) {
            $table->decimal('late_fee', 15, 2)->default(0)->after('paid_amount');
        });
    }

    public function down(): void
    {
        Schema::table('installment_payments', function (Blueprint $table) {
            $table->dropColumn('late_fee');
        });
    }
};
