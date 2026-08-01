<?php

namespace Tests\Unit\Services;

use App\Models\Setting;
use App\Services\MoneyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoneyServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_symbol_defaults_to_naira(): void
    {
        $this->assertEquals('₦', MoneyService::symbol());
    }

    public function test_symbol_uses_setting_when_present(): void
    {
        Setting::create([
            'store_name' => 'OwnPace',
            'currency' => 'NGN',
            'currency_symbol' => '₦',
        ]);

        $this->assertEquals('₦', MoneyService::symbol());
    }

    public function test_format_adds_symbol_and_thousands_separators(): void
    {
        $this->assertEquals('₦1,250,000', MoneyService::format(1250000));
        $this->assertEquals('₦1,250,000.50', MoneyService::format(1250000.5, 2));
    }

    public function test_plain_formats_without_symbol(): void
    {
        $this->assertEquals('1,250,000', MoneyService::plain(1250000));
    }

    public function test_round_rounds_monetary_values(): void
    {
        $this->assertEquals(10.57, MoneyService::round(10.565));
        $this->assertEquals(10.56, MoneyService::round(10.564));
    }

    public function test_parse_strips_symbols_spaces_and_separators(): void
    {
        $this->assertEquals(1250000.0, MoneyService::parse('₦1,250,000'));
        $this->assertEquals(1250000.0, MoneyService::parse('1 250 000'));
        $this->assertEquals(1250000.5, MoneyService::parse('₦1,250,000.50'));
    }

    public function test_percent_of_clamps_to_0_and_100(): void
    {
        $this->assertEquals(50.0, MoneyService::percentOf(50, 100));
        $this->assertEquals(0.0, MoneyService::percentOf(0, 100));
        $this->assertEquals(100.0, MoneyService::percentOf(150, 100));
        $this->assertEquals(0.0, MoneyService::percentOf(10, 0));
    }
}
