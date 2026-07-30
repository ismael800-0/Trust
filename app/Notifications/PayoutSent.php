<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class PayoutSent extends Notification
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
            'type' => 'payout_sent',
            'message' => "🎉 You received a payout of " . number_format($this->amount) . " CFA from '{$this->tontineName}' (Round {$this->round})!",
        ];
    }
}