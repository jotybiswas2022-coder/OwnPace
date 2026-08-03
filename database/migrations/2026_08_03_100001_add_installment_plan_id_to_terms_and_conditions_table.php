<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('terms_and_conditions', function (Blueprint $table) {
            // Null = global terms (shown on every checkout); set = terms
            // scoped to that specific installment plan.
            $table->foreignId('installment_plan_id')->nullable()->after('type')
                ->constrained('installment_plans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('terms_and_conditions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('installment_plan_id');
        });
    }
};
