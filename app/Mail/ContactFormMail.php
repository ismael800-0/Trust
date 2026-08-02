<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public string $senderMessage
    ) {}

    public function build()
    {
        return $this->subject("New Contact Form Message from {$this->senderName}")
            ->replyTo($this->senderEmail, $this->senderName)
            ->view('emails.contact-form');
    }
}