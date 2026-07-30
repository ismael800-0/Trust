<?php

namespace App\Console\Commands;

use App\Models\WalletTransaction;
use Illuminate\Console\Command;

class ReconcileDeposits extends Command
{
    protected $signature = 'wallet:reconcile-stuck';
    protected $description = 'Manually complete deposits confirmed complete on NotchPay but stuck pending locally';

    public function handle()
    {
        $stuck = WalletTransaction::where('status', 'pending')
            ->where('type', 'deposit')
            ->get();

        if ($stuck->isEmpty()) {
            $this->info('No stuck transactions found.');
            return;
        }

        foreach ($stuck as $tx) {
            $this->info("Found: {$tx->reference} - {$tx->amount} CFA - created {$tx->created_at}");
        }

        if ($this->confirm('Mark these as completed and credit wallets? Only confirm after checking NotchPay dashboard for each one!')) {
            foreach ($stuck as $tx) {
                $tx->update(['status' => 'completed']);
                $tx->wallet->credit($tx->amount);
                $this->info("✔ Completed and credited: {$tx->reference}");
            }
        }
    }
}