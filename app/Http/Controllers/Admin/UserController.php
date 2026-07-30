<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('createdTontines')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

   public function toggleActive(User $user)
{
    if ($user->id === auth()->id()) {
        return back()->with('error', "You can't deactivate your own account.");
    }

    $user->is_active = !$user->is_active;
    $user->save();

    if (!$user->is_active) {
        // Deactivating: renumber remaining active members in every tontine, same as removeMember()
        $tontines = $user->tontines()->wherePivot('status', 'active')->get();

        foreach ($tontines as $tontine) {
            $remaining = $tontine->members()
                ->wherePivot('status', 'active')
                ->where('is_active', true)
                ->orderBy('tontine_user.position_in_cycle')
                ->get();

            foreach ($remaining as $index => $member) {
                $tontine->members()->updateExistingPivot($member->id, [
                    'position_in_cycle' => $index + 1,
                ]);
            }
        }

        return back()->with('success', 'User deactivated and rotation positions renumbered where needed.');
    }

    // Reactivating: flag every active tontine this user belongs to for organizer review,
    // since they need to be manually re-inserted into the rotation at an appropriate position
    $tontines = $user->tontines()->wherePivot('status', 'active')->get();

    foreach ($tontines as $tontine) {
        \App\Models\TontineFlag::create([
            'tontine_id' => $tontine->id,
            'message' => "Member '{$user->name}' was reactivated. They are not currently assigned a rotation position — please review and manually assign one via 'Adjust Rotation Order' if they should rejoin the payout cycle.",
        ]);
    }

    return back()->with('success', 'User reactivated. Organizers have been flagged to review rotation placement.');
}

    public function updateRole(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', "You can't change your own role.");
        }

        $request->validate([
            'role' => 'required|in:member,organizer,super_admin',
        ]);

        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'User role updated.');
    }
}