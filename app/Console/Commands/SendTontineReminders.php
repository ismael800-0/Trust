<?php

namespace App\Console\Commands;

use App\Mail\ContributionReminderMail;
use App\Models\Tontine;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTontineReminders extends Command
{
    protected $signature = 'tontine:send-reminders';
    protected $description = 'Send contribution reminders to tontine members based on frequency';

    public function handle(): void
    {
        $tontines = Tontine::where('status', 'active')->get();

        foreach ($tontines as $tontine) {
            $dueDate = $tontine->nextDueDate();
            $isTomorrow = $dueDate->isTomorrow();
            $isOverdue = $dueDate->isPast() && !$dueDate->isToday();

            if (!$isTomorrow && !$isOverdue) {
                continue;
            }

            $currentCycle = $tontine->currentCycle();

            $paidUserIds = $tontine->contributions()
                ->where('round_number', $tontine->current_round)
                ->where('cycle_number', $currentCycle)
                ->where('status', 'paid')
                ->pluck('user_id');

            $unpaidMembers = $tontine->members()
                ->whereNotIn('users.id', $paidUserIds)
                ->get();

            foreach ($unpaidMembers as $member) {
                Mail::to($member->email)->send(
                    new ContributionReminderMail($member, $tontine, $dueDate, $isOverdue)
                );

                $type = $isOverdue ? 'overdue' : 'upcoming';
                $this->info("Sent {$type} reminder to {$member->email} for {$tontine->name}");
            }
        }
    }
}