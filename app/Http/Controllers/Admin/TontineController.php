<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tontine;
use Illuminate\Http\Request;

class TontineController extends Controller
{
    public function index()
    {
        $tontines = Tontine::with('creator')->withCount('members')->latest()->paginate(20);
        return view('admin.tontines.index', compact('tontines'));
    }

    public function suspend(Tontine $tontine)
    {
        $tontine->status = 'archived';
        $tontine->save();

        return back()->with('success', "Tontine '{$tontine->name}' has been archived.");
    }

    public function reactivate(Tontine $tontine)
    {
        $tontine->status = 'active';
        $tontine->save();

        return back()->with('success', "Tontine '{$tontine->name}' has been reactivated.");
    }
}