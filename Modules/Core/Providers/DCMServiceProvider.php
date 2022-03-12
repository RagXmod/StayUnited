<?php

/**
 * Module Core Providers: Modules\Core\Providers\DCMServiceProvider
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */ 


namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Nwidart\Modules\Facades\Module;
use Nwidart\Modules\LaravelModulesServiceProvider;

class DCMServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(LaravelModulesServiceProvider::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('Module', Module::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
