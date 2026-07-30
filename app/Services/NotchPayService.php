<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NotchPayService
{
    protected $publicKey;
    protected $privateKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->publicKey  = config('services.notchpay.public_key') ?? env('NOTCHPAY_PUBLIC_KEY');
        $this->privateKey = config('services.notchpay.private_key') ?? env('NOTCHPAY_PRIVATE_KEY');
        $this->baseUrl    = 'https://api.notchpay.co';
    }

    public function directCharge($amount, $phone, $email, $channel, $reference)
{
    $cleanPhone = str_replace(['+', ' '], '', $phone);
    if (!str_starts_with($cleanPhone, '237')) {
        $cleanPhone = '237' . $cleanPhone;
    }
    $formattedPhone = '+' . $cleanPhone;

    // Step 1: Initialize the payment
    $initResponse = Http::withHeaders([
        'Authorization' => $this->publicKey,
        'Accept'        => 'application/json',
    ])->post("{$this->baseUrl}/payments/initialize", [
        'amount'    => (int) $amount,
        'currency'  => 'XAF',
        'reference' => $reference,
        'email'     => $email,
        'customer'  => [
            'phone' => $formattedPhone,
            'email' => $email,
        ],
    ]);

    if ($initResponse->failed()) {
        throw new \Exception('Notch Pay Initialize Failed: ' . $initResponse->body());
    }

    $initData = $initResponse->json();
    $paymentReference = $initData['transaction']['reference'] ?? $reference;

    // Step 2: Process/charge the payment via Mobile Money
    $chargeResponse = Http::withHeaders([
        'Authorization' => $this->publicKey,
        'Accept'        => 'application/json',
    ])->post("{$this->baseUrl}/payments/{$paymentReference}", [
        'channel' => $channel,
        'data' => [
            'phone' => $formattedPhone,
        ],
    ]);

    if ($chargeResponse->failed()) {
        throw new \Exception('Notch Pay Charge Failed: ' . $chargeResponse->body());
    }

    return $chargeResponse->json();
}

    public function sendPayout($phoneNumber, $amount, $name, $reference, $channel = 'cm.mtn')
{
    $cleanPhone = str_replace(['+', ' '], '', $phoneNumber);
    if (!str_starts_with($cleanPhone, '237')) {
        $cleanPhone = '237' . $cleanPhone;
    }
    $formattedPhone = '+' . $cleanPhone;

    $response = Http::withHeaders([
        'Authorization' => $this->publicKey,
        'X-Grant'       => $this->privateKey,
        'Accept'        => 'application/json',
    ])->post("{$this->baseUrl}/transfers", [
        'amount'      => (int) $amount,
        'currency'    => 'XAF',
        'channel'     => $channel,
        'reference'   => $reference,
        'description' => 'Wallet transaction',
        'recipient'   => [
            'name'           => $name,
            'account_number' => $formattedPhone,
            'email'          => 'member_' . $cleanPhone . '@tontine.local',
        ],
    ]);

    if ($response->failed()) {
        throw new \Exception('Notch Pay Payout failed: ' . $response->body());
    }

    return $response->json();
}

}