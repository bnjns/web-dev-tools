<?php

namespace bnjns\WebDevTools\Providers;

use bnjns\WebDevTools\Auth\LaravelStatusedUserProvider as UserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class UserProviderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Auth::provider('eloquent', function ($app, array $config) {
            return new UserProvider($app['hash'], $config['model']);
        });
    }
}
