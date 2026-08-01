<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\PaymentTransaction;
use App\Models\BankAccount;
use App\Services\MoneyService;
use App\Services\WalletService;
use App\Services\Payments\PaymentGatewayManager;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wallet = WalletService::walletFor(auth()->user());

        $transactions = WalletTransaction::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        $withdrawable = WalletService::withdrawableBalance($wallet);

        return view('frontend.wallet.index', compact('wallet', 'transactions'))
            ->with([
                'withdrawableBalance' => $withdrawable,
                'spendableBalance' => MoneyService::round((float) $wallet->balance - $withdrawable),
            ]);
    }

    public function showFundForm()
    {
        $wallet = auth()->user()->wallet;
        return view('frontend.wallet.fund', compact('wallet'));
    }

    /**
     * Fund the wallet through the shared gateway abstraction. Creates a
     * wallet_funding transaction and hands off to the chosen gateway. On
     * completion the webhook (PaymentController) credits the wallet, applies
     * the top-up bonus, and sets the withdrawable flag per settings.
     */
    public function fund(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100|max:10000000',
            'gateway' => 'required|in:paystack,flutterwave,korapay',
        ]);

        $transaction = PaymentTransaction::create([
            'user_id' => auth()->id(),
            'transaction_reference' => 'WAL-' . strtoupper(Str::random(12)),
            'gateway' => $request->gateway,
            'amount' => $request->amount,
            'currency' => 'NGN',
            'status' => 'pending',
            'type' => 'wallet_funding',
        ]);

        try {
            $result = app(PaymentGatewayManager::class)
                ->driver($request->gateway)
                ->initialize($transaction);

            if ($result['success'] && $result['url']) {
                return redirect($result['url']);
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('wallet.index')
            ->with('error', $result['message'] ?? 'Payment could not be started. Please try again.');
    }

    /**
     * Withdrawal form — only reachable when there is a withdrawable balance.
     */
    public function withdraw()
    {
        $wallet = WalletService::walletFor(auth()->user());
        $withdrawable = WalletService::withdrawableBalance($wallet);

        if ($withdrawable <= 0) {
            return redirect()->route('wallet.index')
                ->with('error', 'You have no withdrawable balance right now.');
        }

        $banks = auth()->user()->bankAccounts;

        return view('frontend.wallet.withdraw', compact('wallet', 'banks'))
            ->with([
                'withdrawableBalance' => $withdrawable,
                'feePercent' => WalletService::withdrawalFeePercent(),
            ]);
    }

    /**
     * Submit a withdrawal request — flat 10% fee, 90% to the linked bank.
     * Funds are held immediately; an admin reviews the request.
     */
    public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'bank_account_id' => 'required|exists:bank_accounts,id',
        ]);

        $bank = BankAccount::where('id', $request->bank_account_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$bank) {
            return back()->with('error', 'Bank account not found.');
        }

        try {
            $withdrawal = WalletService::requestWithdrawal(
                auth()->user(),
                (float) $request->amount,
                $bank
            );
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('wallet.index')->with(
            'success',
            'Withdrawal of ₦' . number_format($withdrawal->amount, 2)
            . ' requested. ₦' . number_format($withdrawal->net_amount, 2)
            . ' will be sent to your bank after review.'
        );
    }

    public function history()
    {
        $transactions = WalletTransaction::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('frontend.wallet.history', compact('transactions'));
    }
}
