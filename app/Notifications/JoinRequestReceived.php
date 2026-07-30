<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class JoinRequestReceived extends Notification
{
    protected $tontineName;
    protected $tontineId;
    protected $applicantName;

    public function __construct($tontineName, $tontineId, $applicantName)
    {
        $this->tontineName = $tontineName;
        $this->tontineId = $tontineId;
        $this->applicantName = $applicantName;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'join_request',
            'message' => "{$this->applicantName} requested to join '{$this->tontineName}'. Review in Manage Members.",
            'tontine_id' => $this->tontineId,
        ];
    }
}