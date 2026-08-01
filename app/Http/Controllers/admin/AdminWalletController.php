<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WalletCreditRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletWithdrawalRequest;
use App\Models\BankAccount;
use App\Services\WalletService;

/**
 * Admin wallet management — manual credits (cashback/rewards/store credit with
 * a withdrawable choice) and review of customer withdrawal requests.
 */
class AdminWalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * List customers with their wallets for quick search + manual credit.
     */
    public function index(Request $request)
    {
        $this->authorize('manage', Wallet::class);

        $query = User::with('wallet')->withCount('orders');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $users = $query->latest()->paginate(15);

        return view('backend.wallet.index', compact('users'));
    }

    /**
     * Manual credit form for one customer.
     */
    public function creditForm(User $user)
    {
        $this->authorize('manage', Wallet::class);

        return view('backend.wallet.credit', compact('user'));
    }

    /**
     * Credit the customer's wallet — admin chooses withdrawable or not.
     */
    public function credit(WalletCreditRequest $request, User $user)
    {
        $this->authorize('manage', Wallet::class);

        WalletService::manualCredit(
            $user,
            (float) $request->amount,
            $request->type,
            $request->description ?? 'Manual credit by ' . auth()->user()->name,
            (bool) $request->withdrawable
        );

        return redirect()->route('admin.wallet.index')
            ->with('success', '₦' . number_format((float) $request->amount, 2)
                . ' credited to ' . $user->name . "'s wallet.");
    }

    /**
     * Withdrawal requests needing admin review.
     */
    public function withdrawals(Request $request)
    {
        $this->authorize('manage', Wallet::class);

        $query = WalletWithdrawalRequest::with(['user', 'bankAccount']);

        if ($request->status && in_array($request->status, WalletWithdrawalRequest::STATUSES)) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(15);

        return view('backend.wallet.withdrawals', compact('requests'));
    }

    /**
     * Advance a withdrawal request: processing → completed/failed.
     * Failed requests return the held funds to the withdrawable pool.
     */
    public function updateWithdrawal(Request $request, WalletWithdrawalRequest $withdrawal)
    {
        $this->authorize('manage', Wallet::class);

        $request->validate([
            'status' => 'required|in:processing,completed,failed',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        if ($withdrawal->status === 'completed' || $withdrawal->status === 'failed') {
            return back()->with('error', 'This request is already finalised.');
        }

        if ($request->status === 'failed') {
            WalletService::failWithdrawal($withdrawal, $request->admin_note);
            return back()->with('success', 'Withdrawal failed — funds returned to the customer\'s withdrawable balance.');
        }

        if ($request->status === 'completed') {
            WalletService::completeWithdrawal($withdrawal, $request->admin_note);
            return back()->with('success', 'Withdrawal marked completed.');
        }

        $withdrawal->update([
            'status' => 'processing',
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Withdrawal marked as processing.');
    }
}
