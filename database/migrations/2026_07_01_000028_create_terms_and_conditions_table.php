<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terms_and_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('type')->default('general'); // general, installment, privacy, refund
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('terms_and_conditions')->insert([
            'title' => 'General Terms & Conditions',
            'content' => 'Welcome to OwnPace. By using our platform, you agree to these terms...',
            'type' => 'general',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('terms_and_conditions');
    }
};
