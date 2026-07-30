<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class OrganizerAnnouncement extends Notification
{
    protected $tontineName;
    protected $message;

    public function __construct($tontineName, $message)
    {
        $this->tontineName = $tontineName;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'announcement',
            'message' => "📢 [{$this->tontineName}]: {$this->message}",
        ];
    }
}