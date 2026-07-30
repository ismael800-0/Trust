<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use App\Models\Tontine;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    /**
     * Show payouts received by the logged-in user across all tontines.
     */
    public function index()
    {
        $payouts = Payout::with('tontine')
            ->where('beneficiary_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payouts.index', compact('payouts'));
    }

    /**
     * Show all payouts for a specific tontine (organizer or member view).
     */
    public function showTontinePayouts($tontineId)
    {
        $tontine = Tontine::findOrFail($tontineId);

        $payouts = Payout::with('beneficiary')
            ->where('tontine_id', $tontineId)
            ->orderBy('round_number')
            ->get();

        return view('payouts.tontine', compact('tontine', 'payouts'));
    }
}