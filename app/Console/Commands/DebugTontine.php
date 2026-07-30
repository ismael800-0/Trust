<?php

namespace App\Console\Commands;

use App\Models\Tontine;
use App\Models\Contribution;
use App\Models\Payout;
use Illuminate\Console\Command;

class DebugTontine extends Command
{
    protected $signature = 'tontine:debug {id}';
    protected $description = 'Dump diagnostic info for a tontine to debug payout issues';

    public function handle()
    {
        $tontine = Tontine::find($this->argument('id'));

        if (!$tontine) {
            $this->error('Tontine not found.');
            return;
        }

        $this->info("Tontine: {$tontine->name}");
        $this->info("Current round: {$tontine->current_round}");
        $this->info("Contribution amount: {$tontine->contribution_amount}");
        $this->info("Total rounds completed: {$tontine->total_rounds_completed}");
        $this->newLine();

        $this->info('--- Active Members ---');
        $members = $tontine->members()->wherePivot('status', 'active')->get();
        foreach ($members as $m) {
            $this->line("ID: {$m->id} | Name: {$m->name} | Position: {$m->pivot->position_in_cycle}");
        }
        $this->newLine();

        $this->info('--- Contributions ---');
        $contributions = Contribution::where('tontine_id', $tontine->id)->orderBy('round_number')->get();
        foreach ($contributions as $c) {
            $this->line("User: {$c->user_id} | Round: {$c->round_number} | Amount: {$c->amount}");
        }
        $this->newLine();

        $this->info('--- Payouts ---');
        $payouts = Payout::where('tontine_id', $tontine->id)->get();
        if ($payouts->isEmpty()) {
            $this->line('(none)');
        }
        foreach ($payouts as $p) {
            $this->line("Beneficiary: {$p->beneficiary_id} | Round: {$p->round_number} | Amount: {$p->amount}");
        }
    }
}