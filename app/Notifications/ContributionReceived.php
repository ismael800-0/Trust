<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ContributionReceived extends Notification
{
    protected $tontineName;
    protected $amount;
    protected $round;

    public function __construct($tontineName, $amount, $round)
    {
        $this->tontineName = $tontineName;
        $this->amount = $amount;
        $this->round = $round;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'contribution_received',
            'message' => "Your contribution of " . number_format($this->amount) . " CFA for '{$this->tontineName}' (Round {$this->round}) was received.",
        ];
    }
}