<?php

namespace App\Providers;

use App\Services\NordigenService;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NordigenService::class, function ($app) {
            return new NordigenService(env('GOCARDLESS_SECRET_ID'), env('GOCARDLESS_SECRET_KEY'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $socialite = $this->app->make(Factory::class);

        $socialite->extend(
            'zauth',
            function ($app) use ($socialite) {
                $config = config('services.zauth');
                return $socialite->buildProvider(ZauthProvider::class, $config);
            }
        );
    }
}
