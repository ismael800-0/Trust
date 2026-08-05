<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{
    protected $signature = 'user:make-admin {email}';
    protected $description = 'Promote a user to super_admin by email';

    public function handle()
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (!$user) {
            $this->error('No user found with that email.');
            return 1;
        }

        $user->role = 'super_admin';
        $user->save();

        $this->info("{$user->name} ({$user->email}) is now super_admin.");
        return 0;
    }
}