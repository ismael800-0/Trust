<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Payout;
use App\Models\Tontine;
use App\Models\TontineFlag;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TontineController extends Controller
{
    public function index()
    {
        $tontines = Auth::user()->tontines()->wherePivot('status', 'active')->get();
        return view('tontines.index', compact('tontines'));
    }

    public function create()
    {
        return view('tontines.create');
    }

    public function store(Request $request)
    {
      

    if (Auth::user()->role === 'super_admin') {
        return back()->with('error', 'Super Admin accounts cannot create tontines. Platform administration is kept separate from tontine participation.');
    }

     $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contribution_amount' => 'required|numeric|min:1',
            'frequency' => 'required|in:daily,weekly,monthly',
            'max_members' => 'required|integer|min:2',
            'start_date' => 'required|date|after_or_equal:today',
        ]);

        $tontine = Tontine::create([
            ...$validated,
            'creator_id' => Auth::id(),
            'status' => 'active',
        ]);

        $tontine->members()->attach(Auth::id(), [
            'status' => 'active',
            'position_in_cycle' => 1,
        ]);

        return redirect()->route('tontines.show', $tontine->id)
            ->with('success', 'Tontine created successfully!');
    }

    public function show($id)
    {
        $tontine = Tontine::with(['members', 'creator'])->findOrFail($id);

        $memberStatus = null;
        $member = $tontine->members()->where('user_id', Auth::id())->first();
        if ($member) {
            $memberStatus = $member->pivot->status;
        }

        $flags = Auth::id() === $tontine->creator_id
            ? TontineFlag::where('tontine_id', $tontine->id)->where('resolved', false)->get()
            : collect();

        return view('tontines.show', compact('tontine', 'memberStatus', 'flags'));
    }

    public function browse()
    {
        $tontines = Tontine::where('status', 'active')
            ->withCount('members')
            ->get();

        return view('tontines.browse', compact('tontines'));
    }

    public function join(Request $request, $id)
    {
        

    if (Auth::user()->role === 'super_admin') {
        return back()->with('error', 'Super Admin accounts cannot join tontines. Platform administration is kept separate from tontine participation.');
    }

   $tontine = Tontine::findOrFail($id);

        $alreadyRequested = $tontine->members()->where('user_id', Auth::id())->exists();

        if ($alreadyRequested) {
            return back()->with('error', 'You have already requested to join or are a member of this tontine.');
        }

        if ($tontine->members()->wherePivot('status', 'active')->count() >= $tontine->max_members) {
            return back()->with('error', 'This tontine has reached its maximum member limit.');
        }

        $tontine->members()->attach(Auth::id(), ['status' => 'pending']);
        $tontine->creator->notify(new \App\Notifications\JoinRequestReceived(
        $tontine->name,
        $tontine->id,
        Auth::user()->name
    ));

        return back()->with('success', 'Join request sent! Waiting for organizer approval.');
    }

    public function manageMembers($id)
    {
        $tontine = Tontine::findOrFail($id);

        if (Auth::id() !== $tontine->creator_id) {
            abort(403, 'Only the organizer can manage members.');
        }

        $members = $tontine->members;
        return view('tontines.manage-members', compact('tontine', 'members'));
    }

    public function approveMember($tontineId, $userId)
    {
        $tontine = Tontine::findOrFail($tontineId);

        if (Auth::id() !== $tontine->creator_id) {
            abort(403);
        }

        $nextPosition = $tontine->members()->wherePivot('status', 'active')->count() + 1;

        $tontine->members()->updateExistingPivot($userId, [
            'status' => 'active',
            'position_in_cycle' => $nextPosition,
        ]);

        return back()->with('success', 'Member approved.');
    }

    public function rejectMember($tontineId, $userId)
    {
        $tontine = Tontine::findOrFail($tontineId);

        if (Auth::id() !== $tontine->creator_id) {
            abort(403);
        }

        $tontine->members()->updateExistingPivot($userId, ['status' => 'rejected']);

        return back()->with('success', 'Join request rejected.');
    }

    public function removeMember($tontineId, $userId)
    {
        $tontine = Tontine::findOrFail($tontineId);

        if (Auth::id() !== $tontine->creator_id) {
            abort(403);
        }

        $removedMember = $tontine->members()->where('user_id', $userId)->first();
        $removedPosition = $removedMember->pivot->position_in_cycle ?? null;

        if ($removedPosition !== null && $removedPosition >= $tontine->current_round) {
            TontineFlag::create([
                'tontine_id' => $tontine->id,
                'message' => "Member '{$removedMember->name}' was removed before their payout turn (was position {$removedPosition}, current round {$tontine->current_round}). Review pool/rotation fairness manually.",
            ]);
        }

        $tontine->members()->detach($userId);

        $remaining = $tontine->members()
            ->wherePivot('status', 'active')
            ->orderBy('tontine_user.position_in_cycle')
            ->get();

        foreach ($remaining as $index => $member) {
            $tontine->members()->updateExistingPivot($member->id, [
                'position_in_cycle' => $index + 1,
            ]);
        }

        return back()->with('success', 'Member removed and rotation renumbered.');
    }

    public function updatePositions(Request $request, $tontineId)
{
    $tontine = Tontine::findOrFail($tontineId);

    if (Auth::id() !== $tontine->creator_id) {
        abort(403);
    }

    $positions = $request->input('positions', []);
    $activeMembers = $tontine->members()->wherePivot('status', 'active')->get();
    $totalActive = $activeMembers->count();

    // 1. Every active member must have a position submitted
    if (count($positions) !== $totalActive) {
        return back()->with('error', 'Position data is incomplete. Please try again.');
    }

    $submittedValues = array_values($positions);

    // 2. All values must be integers between 1 and totalActive
    foreach ($submittedValues as $value) {
        if (!is_numeric($value) || (int)$value < 1 || (int)$value > $totalActive) {
            return back()->with('error', "Positions must be numbers between 1 and {$totalActive}.");
        }
    }

    // 3. No duplicate positions allowed
    if (count(array_unique($submittedValues)) !== count($submittedValues)) {
        return back()->with('error', 'Each member must have a unique position — no duplicates allowed.');
    }

    // 4. Every submitted user ID must actually belong to this tontine's active members
    $validUserIds = $activeMembers->pluck('id')->toArray();
    foreach (array_keys($positions) as $userId) {
        if (!in_array((int)$userId, $validUserIds)) {
            return back()->with('error', 'Invalid member reference detected.');
        }
    }

    // All checks passed — apply the update
    foreach ($positions as $userId => $position) {
        $tontine->members()->updateExistingPivot($userId, [
            'position_in_cycle' => (int) $position,
        ]);
    }

    return back()->with('success', 'Rotation order updated successfully.');
}

    public function edit($id)
    {
        $tontine = Tontine::findOrFail($id);

        if (Auth::id() !== $tontine->creator_id) {
            abort(403);
        }

        return view('tontines.edit', compact('tontine'));
    }

    public function update(Request $request, $id)
{
    $tontine = Tontine::findOrFail($id);

    if (Auth::id() !== $tontine->creator_id) {
        abort(403);
    }

    $activeCount = $tontine->members()->wherePivot('status', 'active')->count();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'max_members' => "required|integer|min:{$activeCount}",
    ]);

    $tontine->update($validated);

    return redirect()->route('tontines.show', $tontine->id)
        ->with('success', 'Tontine updated.');
}

    public function contributeForm($tontineId)
    {
        $tontine = Tontine::findOrFail($tontineId);
        return view('contributions.create', compact('tontine'));
    }

    public function resolveFlag($flagId)
    {
        $flag = TontineFlag::findOrFail($flagId);

        if (Auth::id() !== $flag->tontine->creator_id) {
            abort(403);
        }

        $flag->update(['resolved' => true]);

        return back()->with('success', 'Flag marked as reviewed.');
    }

    /**
     * Check if a round is complete, and if so, process the payout
     * to the current rotation beneficiary and advance to the next round.
     */
    public function evaluateAndProcessRound(Tontine $tontine, int $roundNumber)
{
    $activeMembers = $tontine->members()
        ->wherePivot('status', 'active')
        ->where('is_active', true)
        ->get();
    $totalActive = $activeMembers->count();

    if ($totalActive === 0) {
        return;
    }

    $paidCount = Contribution::where('tontine_id', $tontine->id)
    ->where('round_number', $roundNumber)
    ->where('cycle_number', $tontine->cycle_number)
    ->count();

    if ($paidCount < $totalActive) {
        return; // round isn't complete yet
    }

    $alreadyPaid = Payout::where('tontine_id', $tontine->id)
    ->where('round_number', $roundNumber)
    ->where('cycle_number', $tontine->cycle_number)
    ->exists();

    if ($alreadyPaid) {
        return; // prevent double payout
    }

    $position = (($roundNumber - 1) % $totalActive) + 1;
    $beneficiary = $activeMembers->firstWhere('pivot.position_in_cycle', $position);

    if (!$beneficiary) {
        Log::error("No beneficiary found for tontine {$tontine->id}, round {$roundNumber}");
        return;
    }

    $totalPool = $tontine->contribution_amount * $totalActive;

    DB::beginTransaction();

    try {
        $wallet = $beneficiary->wallet;
        $wallet->credit($totalPool);

        $walletTransaction = WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'payout',
            'amount' => $totalPool,
            'reference' => 'PAYOUT-' . strtoupper(Str::random(10)),
            'status' => 'completed',
            'description' => "Payout from '{$tontine->name}' — Round {$roundNumber}",
        ]);

        Payout::create([
    'tontine_id' => $tontine->id,
    'beneficiary_id' => $beneficiary->id,
    'amount' => $totalPool,
    'round_number' => $roundNumber,
    'cycle_number' => $tontine->cycle_number,
    'wallet_transaction_id' => $walletTransaction->id,
]);
        $tontine->current_round = $roundNumber + 1;
        $tontine->last_disbursement_at = now();
        $tontine->total_rounds_completed = ($tontine->total_rounds_completed ?? 0) + 1;

        // Close the tontine once every active member has received exactly one payout
        if ($tontine->total_rounds_completed >= $totalActive) {
            $tontine->status = 'completed';
        }

        $tontine->save();

        DB::commit();
        $beneficiary->notify(new \App\Notifications\PayoutSent($tontine->name, $totalPool, $roundNumber));
        Log::info("Round {$roundNumber} completed for tontine {$tontine->id}. Payout of {$totalPool} CFA sent to user {$beneficiary->id}.");

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Payout processing failed for tontine {$tontine->id}, round {$roundNumber}: " . $e->getMessage());
        throw $e;
    }
}

public function renew($id)
{
    $tontine = Tontine::findOrFail($id);

    if (Auth::id() !== $tontine->creator_id) {
        abort(403);
    }

    if ($tontine->status !== 'completed') {
        return back()->with('error', 'Only a completed tontine can be renewed.');
    }

    $tontine->cycle_number += 1;
    $tontine->current_round = 1;
    $tontine->total_rounds_completed = 0;
    $tontine->status = 'active';
    $tontine->save();

    return back()->with('success', "New cycle started! Cycle {$tontine->cycle_number} begins at Round 1.");
}

public function destroy($id)
{
    $tontine = Tontine::findOrFail($id);

    if (Auth::id() !== $tontine->creator_id) {
        abort(403);
    }

    $name = $tontine->name;
    $tontine->delete(); // cascades to members, contributions, payouts, flags via FK constraints

    return redirect()->route('tontines.index')->with('success', "Tontine '{$name}' has been permanently deleted.");
}

}