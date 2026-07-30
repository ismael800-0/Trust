<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('NotchPay Webhook Received:', $request->all());

        $event = $request->input('event');
        $data = $request->input('data');

        if (!$data) {
            return response()->json(['status' => 'no data'], 400);
        }

        $merchantReference = $data['merchant_reference'] ?? null;
        $notchReference = $data['reference'] ?? null;

        $transaction = WalletTransaction::where('reference', $merchantReference)->first();

        if (!$transaction) {
            Log::warning('Webhook: no matching wallet transaction for reference ' . $merchantReference);
            return response()->json(['status' => 'transaction not found'], 200);
        }

        // Always store NotchPay's own reference for reconciliation, separate from ours
        if ($notchReference && !$transaction->notchpay_reference) {
            $transaction->update(['notchpay_reference' => $notchReference]);
        }

        switch ($event) {
            case 'payment.complete':
            case 'transaction.complete':
                if ($transaction->type === 'deposit' && $transaction->status !== 'completed') {
                    $transaction->update(['status' => 'completed']);
                    $transaction->wallet->credit($transaction->amount);
                    Log::info('Deposit completed and wallet credited: ' . $merchantReference);
                }
                break;

            case 'transfer.complete':
                if ($transaction->type === 'withdrawal' && $transaction->status !== 'completed') {
                    // Balance was already debited at withdrawal time; just mark completed
                    $transaction->update(['status' => 'completed']);
                    Log::info('Withdrawal completed: ' . $merchantReference);
                }
                break;

            case 'payment.failed':
            case 'transaction.failed':
                if ($transaction->type === 'deposit' && $transaction->status !== 'failed') {
                    $transaction->update(['status' => 'failed']);
                    Log::info('Deposit failed: ' . $merchantReference);
                }
                break;

            case 'transfer.failed':
                if ($transaction->type === 'withdrawal' && $transaction->status !== 'failed') {
                    $transaction->update(['status' => 'failed']);
                    $transaction->wallet->credit($transaction->amount); // refund the debit
                    Log::info('Withdrawal failed, wallet refunded: ' . $merchantReference);
                }
                break;

            case 'payment.processing':
                if ($transaction->type === 'deposit' && $transaction->status === 'pending') {
                    $transaction->update(['status' => 'processing']);
                    Log::info('Deposit now processing: ' . $merchantReference);
               }
               break;
            default:
                Log::info('Unhandled webhook event: ' . $event);
        }

        return response()->json(['status' => 'processed']);
    }
}