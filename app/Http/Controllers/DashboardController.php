<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Payout;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

public function index()
{
    $user = Auth::user();
    $wallet = $user->wallet;

    $activeTontines = $user->tontines()
        ->wherePivot('status', 'active')
        ->where('tontines.status', 'active')
        ->with(['members' => function ($q) {
            $q->wherePivot('status', 'active')->where('is_active', true);
        }])
        ->get();

    $completedTontines = $user->tontines()
        ->wherePivot('status', 'active')
        ->where('tontines.status', 'completed')
        ->get();

    $dismissedIds = \DB::table('dismissed_activities')
        ->where('user_id', $user->id)
        ->pluck('wallet_transaction_id');

    $recentWallet = WalletTransaction::where('wallet_id', $wallet->id)
        ->whereNotIn('id', $dismissedIds)
        ->latest()
        ->take(5)
        ->get()
        ->map(fn ($t) => [
            'id' => $t->id,
            'type' => $t->type,
            'amount' => $t->amount,
            'description' => $t->description,
            'created_at' => $t->created_at,
        ]);

    $recentActivity = $recentWallet->sortByDesc('created_at')->take(8)->values();

    return view('dashboard', compact('wallet', 'activeTontines', 'completedTontines', 'recentActivity'));
}

}