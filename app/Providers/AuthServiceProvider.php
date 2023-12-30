<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use App\Providers\MD5AuthProvider; // Import your MD5AuthProvider
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        Auth::provider('md5', function ($app, array $config) {
            return new MD5AuthProvider($app['hash'], $config['model']);
        });
    }
}
