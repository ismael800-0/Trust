<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Tontine;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ContributionController extends Controller
{
    /**
     * Pay the current round's contribution directly from wallet balance.
     */
   public function store(Request $request, $tontineId)
{
    $tontine = Tontine::findOrFail($tontineId);

    if ($tontine->status === 'completed') {
        return back()->with('error', 'This tontine has completed its full cycle. No further contributions are accepted.');
    }

    $isMember = $tontine->members()
        ->where('user_id', Auth::id())
        ->wherePivot('status', 'active')
        ->exists();

    if (!$isMember) {
        return back()->with('error', 'You must be an approved active member to contribute.');
    }

    if (!Auth::user()->is_active) {
        return back()->with('error', 'Your account has been deactivated. Please contact the platform administrator.');
    }

    $currentRound = $tontine->current_round ?? 1;

    $alreadyContributed = Contribution::where('tontine_id', $tontine->id)
    ->where('user_id', Auth::id())
    ->where('round_number', $currentRound)
    ->where('cycle_number', $tontine->cycle_number)
    ->exists();

    if ($alreadyContributed) {
        return back()->with('error', "You have already contributed for Round {$currentRound}.");
    }

    $wallet = Auth::user()->wallet;
    $amount = $tontine->contribution_amount;

    if ($wallet->balance < $amount) {
        return back()->with('error', 'Insufficient wallet balance. Please deposit funds first.')
            ->with('insufficient_funds', true);
    }

    DB::beginTransaction();

    try {
        $wallet->debit($amount);

        $walletTransaction = WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'contribution',
            'amount' => $amount,
            'reference' => 'CONTRIB-' . strtoupper(Str::random(10)),
            'status' => 'completed',
            'description' => "Contribution to '{$tontine->name}' — Round {$currentRound}",
        ]);

        $contribution = Contribution::create([
    'tontine_id' => $tontine->id,
    'user_id' => Auth::id(),
    'amount' => $amount,
    'round_number' => $currentRound,
    'cycle_number' => $tontine->cycle_number,
    'wallet_transaction_id' => $walletTransaction->id,
    'status' => 'paid',
]);

        DB::commit();

        // Notify the contributor
        Auth::user()->notify(new \App\Notifications\ContributionReceived($tontine->name, $amount, $currentRound));

        // Round-completion check (non-blocking; failure here shouldn't undo the payment)
        try {
            app(TontineController::class)->evaluateAndProcessRound($tontine, $currentRound);
        } catch (\Exception $roundEx) {
            Log::error('Round evaluation failed: ' . $roundEx->getMessage());
        }

        return back()->with('success', 'Contribution of ' . number_format($amount) . ' CFA paid successfully from your wallet.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Contribution failed: ' . $e->getMessage());
        return back()->with('error', 'Something went wrong processing your contribution. Please try again.');
    }
}
    public function index()
    {
        $contributions = Contribution::with('tontine')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('contributions.index', compact('contributions'));
    }

    public function mycontributions()
    {
        return $this->index();
    }

    public function approvals()
    {
        // With wallet-based contributions, payments complete instantly —
        // this view can now double as a read-only ledger for organizers
        // rather than a pending-approval queue.
        $managedTontineIds = Tontine::where('creator_id', Auth::id())->pluck('id');

        $recentContributions = Contribution::with(['tontine', 'user'])
            ->whereIn('tontine_id', $managedTontineIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('approval', ['pendingPayments' => $recentContributions]);
    }
}