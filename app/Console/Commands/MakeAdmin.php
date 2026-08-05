<?php

namespace App\Console\Commands;
namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
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

