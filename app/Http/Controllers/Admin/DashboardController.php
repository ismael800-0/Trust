<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tontine;
use App\Models\Contribution;
use App\Models\Payout;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalTontines' => Tontine::count(),
            'activeTontines' => Tontine::where('status', 'active')->count(),
            'completedTontines' => Tontine::where('status', 'completed')->count(),
            'totalContributions' => Contribution::sum('amount'),
            'totalPayouts' => Payout::sum('amount'),
        ];

        $recentTontines = Tontine::with('creator')->latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentTontines', 'recentUsers'));
    }
}