<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('store_name')->default('OwnPace');
            $table->text('store_description')->nullable();
            $table->string('currency')->default('NGN');
            $table->string('currency_symbol')->default('₦');
            $table->decimal('default_interest_rate', 5, 2)->default(10);
            $table->decimal('cancellation_fee_percentage', 5, 2)->default(10);
            $table->decimal('delivery_threshold_percentage', 5, 2)->default(70);
            $table->boolean('insurance_enabled')->default(true);
            $table->string('primary_color')->default('#2563EB');
            $table->string('accent_color')->default('#22C55E');
            $table->text('logo')->nullable();
            $table->text('favicon')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('timezone')->default('Africa/Lagos');
            $table->string('default_gateway')->nullable();
            $table->json('gateway_config')->nullable();
            $table->text('smtp_settings')->nullable();
            $table->text('sms_settings')->nullable();
            $table->boolean('registration_enabled')->default(true);
            $table->boolean('guest_checkout')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'store_name', 'store_description', 'currency', 'currency_symbol',
                'default_interest_rate', 'cancellation_fee_percentage',
                'delivery_threshold_percentage', 'insurance_enabled',
                'primary_color', 'accent_color', 'logo', 'favicon',
                'meta_description', 'meta_keywords', 'timezone', 'default_gateway',
                'gateway_config', 'smtp_settings', 'sms_settings',
                'registration_enabled', 'guest_checkout'
            ]);
        });
    }
};
