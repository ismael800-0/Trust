<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BrevoMailService
{
    public function send(string $toEmail, string $toName, string $subject, string $htmlContent, ?string $replyToEmail = null): bool
    {
        $payload = [
            'sender' => [
                'name' => config('mail.from.name'),
                'email' => config('mail.from.address'),
            ],
            'to' => [
                ['email' => $toEmail, 'name' => $toName],
            ],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
        ];

        if ($replyToEmail) {
            $payload['replyTo'] = ['email' => $replyToEmail];
        }

        $response = Http::withHeaders([
            'api-key' => config('services.brevo.api_key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', $payload);

        return $response->successful();
    }
}