<?php

namespace Modules\User\Providers;

use Auth;
use Modules\User\Guards\SentinelGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
/**
 * Class AuthServiceProvider
 *
 * @package  Modules\User\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Auth::extend('sentinel', function ($app, $name, array $config) {
            return new SentinelGuard($app['sentinel'], Auth::createUserProvider($config['provider']));
        });
    }
}