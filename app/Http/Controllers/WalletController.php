<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use App\Services\NotchPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    protected $notchPay;

    public function __construct(NotchPayService $notchPay)
    {
        $this->notchPay = $notchPay;
    }

    public function index()
{
    $wallet = Auth::user()->wallet;
    $transactions = $wallet->transactions()->latest()->paginate(20);
    $feePercentage = config('services.platform.fee_percentage', 2);

    return view('wallet.index', compact('wallet', 'transactions', 'feePercentage'));
}

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'phone_number' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $wallet = Auth::user()->wallet;
        $reference = 'DEP-' . strtoupper(Str::random(12));
        $channel = ($request->payment_method === 'Orange Money') ? 'cm.orange' : 'cm.mtn';

        $transaction = WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'deposit',
            'amount' => $request->amount,
            'reference' => $reference,
            'status' => 'pending',
            'description' => 'Wallet deposit via ' . $request->payment_method,
        ]);

        try {
            $response = $this->notchPay->directCharge(
                $request->amount,
                $request->phone_number,
                Auth::user()->email,
                $channel,
                $reference
            );

            return back()->with('success', 'Deposit initiated. Please confirm on your phone. Your balance will update once payment is confirmed.');

        } catch (\Throwable $e) {
            $transaction->update(['status' => 'failed']);
            Log::error('Wallet deposit failed: ' . $e->getMessage());
            return back()->with('error', 'Deposit failed: ' . $e->getMessage());
        }
    }

  public function withdraw(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:100',
        'phone_number' => 'required|string',
        'payment_method' => 'required|string',
    ]);

    $wallet = Auth::user()->wallet;
    $requestedAmount = $request->amount;

    $feePercentage = config('services.platform.fee_percentage', 2);
    $fee = round($requestedAmount * ($feePercentage / 100), 2);
    $totalDebit = $requestedAmount + $fee; // fee is on top of the withdrawal, not taken from it

    if ($totalDebit > $wallet->balance) {
        return back()->with('error', 'Insufficient wallet balance to cover withdrawal amount plus platform fee.');
    }

    $channel = ($request->payment_method === 'Orange Money') ? 'cm.orange' : 'cm.mtn';
    $reference = 'WDR-' . strtoupper(Str::random(12));

    $transaction = WalletTransaction::create([
        'wallet_id' => $wallet->id,
        'type' => 'withdrawal',
        'amount' => $requestedAmount,
        'reference' => $reference,
        'status' => 'pending',
        'description' => "Withdrawal of {$requestedAmount} CFA to {$request->phone_number} via {$request->payment_method} — plus {$feePercentage}% platform fee ({$fee} CFA) deducted separately",
    ]);

    try {
        // Debit the full amount (withdrawal + fee) from the user's wallet up front
        $wallet->debit($totalDebit);

        // Send the FULL requested amount to the user — fee is not subtracted from payout
        $response = $this->notchPay->sendPayout(
            $request->phone_number,
            $requestedAmount,
            Auth::user()->name,
            $reference,
            $channel
        );

        $feeAccountEmail = config('services.platform.fee_account_email');
        if ($feeAccountEmail) {
            $feeAccount = \App\Models\User::where('email', $feeAccountEmail)->first();

            if ($feeAccount && $feeAccount->wallet) {
                $feeAccount->wallet->credit($fee);

                WalletTransaction::create([
                    'wallet_id' => $feeAccount->wallet->id,
                    'type' => 'fee',
                    'amount' => $fee,
                    'reference' => 'FEE-' . strtoupper(Str::random(10)),
                    'status' => 'completed',
                    'description' => "Platform fee ({$feePercentage}%) from withdrawal by " . Auth::user()->name . " (ref: {$reference})",
                ]);
            } else {
                Log::warning("Platform fee account not found or missing wallet: {$feeAccountEmail}");
            }
        }

        return back()->with('success', "Withdrawal of {$requestedAmount} CFA initiated. A separate platform fee of {$fee} CFA ({$feePercentage}%) was deducted from your wallet balance.");

    } catch (\Throwable $e) {
        // Refund the full debited amount (withdrawal + fee) since nothing succeeded
        $wallet->credit($totalDebit);
        $transaction->update(['status' => 'failed']);
        Log::error('Wallet withdrawal failed: ' . $e->getMessage());
        return back()->with('error', 'Withdrawal failed: ' . $e->getMessage());
    }
}

}