<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliveryAddress;
use App\Models\UserVerification;
use App\Models\SavedCard;
use App\Models\BankAccount;
use App\Models\AccountDeletionRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user()->load(['orders' => function($q) {
            $q->latest()->take(5);
        }, 'wallet', 'deliveryAddresses', 'verifications', 'savedCards', 'bankAccounts']);

        $deletionRequest = AccountDeletionRequest::where('user_id', $user->id)->latest()->first();

        // Verification status per panel item. Document types come from the
        // user_verifications table; email and store terms have their own flags.
        $verificationStatuses = [];
        $verificationTypes = ['identity_card', 'payment_card', 'bank_account', 'delivery_address'];
        foreach ($verificationTypes as $type) {
            $verificationStatuses[$type] = $user->verifications->firstWhere('type', $type)?->status ?? 'unsubmitted';
        }
        $verificationStatuses['email'] = $user->email_verified_at ? 'approved' : 'unsubmitted';
        $terms = $user->store_terms_acceptance;
        $verificationStatuses['store_terms'] = in_array($terms, ['accepted', 'approved']) ? 'approved'
            : (in_array($terms, ['pending', 'under_review']) ? 'pending' : 'unsubmitted');

        return view('frontend.profile.index', compact('user', 'deletionRequest', 'verificationStatuses'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('frontend.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['name', 'phone']);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:6|confirmed',
        ]);

        auth()->user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully!');
    }

    // ===== Address Management =====
    public function addresses()
    {
        $addresses = auth()->user()->deliveryAddresses;
        return view('frontend.profile.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'label' => 'nullable|string|max:50',
            'is_default' => 'boolean',
        ]);

        $addressCount = auth()->user()->deliveryAddresses()->count();
        if ($addressCount >= 5) {
            return back()->with('error', 'You can only have up to 5 delivery addresses.');
        }

        if ($request->is_default) {
            auth()->user()->deliveryAddresses()->update(['is_default' => false]);
        }

        auth()->user()->deliveryAddresses()->create($request->all());

        return back()->with('success', 'Address added successfully!');
    }

    public function updateAddress(Request $request, DeliveryAddress $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        if ($request->is_default) {
            auth()->user()->deliveryAddresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($request->all());
        return back()->with('success', 'Address updated!');
    }

    public function deleteAddress(DeliveryAddress $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }
        $address->delete();
        return back()->with('success', 'Address deleted!');
    }

    // ===== Cards Management =====
    public function cards()
    {
        $cards = auth()->user()->savedCards;
        return view('frontend.profile.cards', compact('cards'));
    }

    public function deleteCard(SavedCard $card)
    {
        if ($card->user_id !== auth()->id()) abort(403);
        $card->delete();
        return back()->with('success', 'Card removed!');
    }

    // ===== Bank Accounts =====
    public function banks()
    {
        $banks = auth()->user()->bankAccounts;
        return view('frontend.profile.banks', compact('banks'));
    }

    public function storeBank(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:20',
            'account_name' => 'required|string|max:255',
            'bank_code' => 'nullable|string|max:10',
        ]);

        auth()->user()->bankAccounts()->create($request->all() + ['status' => 'pending']);
        return back()->with('success', 'Bank account added!');
    }

    public function deleteBank(BankAccount $bank)
    {
        if ($bank->user_id !== auth()->id()) abort(403);
        $bank->delete();
        return back()->with('success', 'Bank account removed!');
    }

    // ===== Verification =====
    public function verification()
    {
        $verifications = auth()->user()->verifications;
        return view('frontend.profile.verification', compact('verifications'));
    }

    public function submitVerification(Request $request)
    {
        $request->validate([
            'type' => 'required|in:identity_card,payment_card,bank_account,delivery_address',
            'document' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'document_number' => 'nullable|string|max:255',
        ]);

        $path = $request->file('document')->store('verifications', 'public');

        UserVerification::updateOrCreate(
            ['user_id' => auth()->id(), 'type' => $request->type],
            [
                'document_path' => $path,
                'document_number' => $request->document_number,
                'status' => 'pending',
            ]
        );

        return back()->with('success', 'Verification submitted for review!');
    }

    // ===== Account Deletion =====
    /**
     * Request account closure. This creates an admin-reviewable request rather
     * than deleting anything — an admin approves/rejects it from the panel.
     */
    public function requestDeletion(Request $request)
    {
        $user = auth()->user();

        if ($user->activeOrders()->count() > 0) {
            return back()->with('error', 'You have active orders. Please complete them before closing your account.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        if (AccountDeletionRequest::where('user_id', $user->id)->where('status', 'pending')->exists()) {
            return back()->with('info', 'You already have a pending account closure request.');
        }

        AccountDeletionRequest::create([
            'user_id' => $user->id,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', "Your account closure request has been submitted. We'll review it shortly.");
    }
}
