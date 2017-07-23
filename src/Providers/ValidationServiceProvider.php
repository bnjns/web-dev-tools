<?php

namespace bnjns\WebDevTools\Providers;

use bnjns\WebDevTools\Validation\LaravelValidator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot()
    {
        $this->app->validator->resolver(function ($translator, $data, $rules, $messages) {
            return new LaravelValidator($translator, $data, $rules, $messages);
        });
    }
    
    /**
     * Register the application services.
     * @return void
     */
    public function register()
    {
        //
    }
}
