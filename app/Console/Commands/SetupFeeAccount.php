<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Console\Command;

class SetupFeeAccount extends Command
{
    protected $signature = 'fee-account:setup {email}';
    protected $description = 'Promote a user to super_admin and ensure they have a wallet';

    public function handle()
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (!$user) {
            $this->error('User not found. Register the account first via /register.');
            return;
        }

        $user->role = 'super_admin';
        $user->save();
        $this->info("Role updated to super_admin for {$user->email}");

        if (!$user->wallet) {
            Wallet::create(['user_id' => $user->id, 'balance' => 0]);
            $this->info('Wallet created.');
        } else {
            $this->info("Wallet already exists, balance: {$user->wallet->balance}");
        }
    }
}