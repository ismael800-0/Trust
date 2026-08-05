<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        User::created(function (User $user) {
            Wallet::create(['user_id' => $user->id, 'balance' => 0]);
        });

        Mail::extend('brevo+api', function (array $config = []) {
        return (new BrevoTransportFactory())->create(
            new Dsn('brevo+api', 'default', $config['key'] ?? null)
        );
    });
    
    }
}