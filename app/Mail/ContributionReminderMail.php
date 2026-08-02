<?php

namespace App\Mail;

use App\Models\Tontine;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContributionReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $member,
        public Tontine $tontine,
        public \Carbon\Carbon $dueDate,
        public bool $isOverdue = false
    ) {}

    public function build()
    {
        $subject = $this->isOverdue
            ? "Overdue: Contribution needed for {$this->tontine->name}"
            : "Reminder: Contribution due for {$this->tontine->name}";

        return $this->subject($subject)
            ->view('emails.contribution-reminder');
    }
}