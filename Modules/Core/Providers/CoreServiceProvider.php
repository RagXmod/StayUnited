<?php

/**
 * Module Core Providers: odules\Core\Providers\CoreServiceProvider
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

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Laravel\Passport\Passport;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Http\Middleware\Localization;
use Modules\Core\Http\Middleware\GlobalSanitizer;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The filters base class name.
     *
     * @var array
     */
    protected $middleware = [
        'sanitizer'    => GlobalSanitizer::class
    ];


    protected $prependToWebMiddlewares = [
        'localization' => Localization::class
    ];


    public function boot()
    {
        $this->registerMiddleware($this->app['router']);
        $this->registerCommands();
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(Hashids::class, function () {
            return new Hashids(env('HASHIDS_SALT','DCM-V1'), env('HASHIDS_SALT_LENGTH',15));
        });


        Blade::if('env', function ($environment) {
            return app()->environment($environment);
        });

        $this->app->singleton('isOnBackend', function () {
            return $this->onBackend();
        });
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


    /**
     * Register the filters.
     *
     * @param  Router $router
     * @return void
     */
    public function registerMiddleware(Router $router)
    {
        foreach ($this->middleware as $name => $class) {
            $router->aliasMiddleware($name, $class);
        }

        if( count($this->prependToWebMiddlewares) > 0 ) {
            foreach( $this->prependToWebMiddlewares as $name => $class) {
                $router->pushMiddlewareToGroup('web', $class);
            }
        }

    }

    public function registerCommands()
    {

        // $commands = [

        // ];
        // $this->commands($commands);
    }

    /**
     * Checks if the current url matches the configured backend uri
     * @return bool
     */
    private function onBackend()
    {
        $url = app(Request::class)->path();
        if (str_contains($url, config('dcm.core.backend.route-prefix'))) {
            return true;
        }
        return false;
    }
}