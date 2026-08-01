<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsRequest;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        return view('backend.settings', compact('settings'));
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

        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
