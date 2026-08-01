<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsRequest;
use App\Models\Setting;
use App\Models\InsuranceSetting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        $insurance = InsuranceSetting::first();
        return view('backend.settings', compact('settings', 'insurance'));
    }

    public function update(SettingsRequest $request)
    {
        $this->authorize('manage', Setting::class);

        $settings = Setting::first() ?? new Setting();

        $settings->email = $request->email;
        $settings->phone = $request->phone;
        $settings->location = $request->location;
        $settings->default_gateway = $request->default_gateway;

        // Save gateway configuration as JSON
        $gatewayConfig = [
            'paystack_public' => $request->paystack_public ?? '',
            'paystack_secret' => $request->paystack_secret ?? '',
            'flutterwave_public' => $request->flutterwave_public ?? '',
            'flutterwave_secret' => $request->flutterwave_secret ?? '',
            'flutterwave_encryption' => $request->flutterwave_encryption ?? '',
            'korapay_public' => $request->korapay_public ?? '',
            'korapay_secret' => $request->korapay_secret ?? '',
        ];
        $settings->gateway_config = $gatewayConfig;

        // Wallet rules.
        $settings->allow_topup_withdrawal = $request->boolean('allow_topup_withdrawal');
        $settings->withdrawal_fee_percent = $request->withdrawal_fee_percent ?? 10;
        $settings->topup_bonus_percent = $request->topup_bonus_percent ?? 0;

        $settings->save();

        // Insurance is storewide: enable/disable + rate, read at checkout.
        $insurance = InsuranceSetting::first() ?? new InsuranceSetting();
        $insurance->name = 'Insurance Fee';
        $insurance->rate = $request->insurance_rate ?? $insurance->rate ?? 10;
        $insurance->type = 'percentage';
        $insurance->is_enabled = $request->boolean('insurance_enabled');
        $insurance->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
