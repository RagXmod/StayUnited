<?php

/**
 * Module Core Providers: odules\Core\Providers\RoutingServiceProvider
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

use Illuminate\Routing\Router;
use Modules\Core\Support\Hashing\Hasher;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

abstract class RoutingServiceProvider extends ServiceProvider
{
    /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $namespace = '';

    protected $configName = 'dcm';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * @return string
     */
    abstract protected function frontendRoute();

    /**
     * @return string
     */
    abstract protected function backendRoute();

    /**
     * @return string
     */
    abstract protected function apiRoute();

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {

        $router->group(['namespace' => $this->namespace], function (Router $router) {
            $this->loadApiRoutes($router);
        });


        $router->group([
            'namespace' => $this->namespace,
            // 'prefix' => LaravelLocalization::setLocale(),
            'middleware' => ['web'],
        ], function (Router $router) {
            $this->loadBackendRoutes($router);
            $this->loadFrontendRoutes($router);
        });
    }

    /**
     * @param Router $router
     */
    private function loadFrontendRoutes(Router $router)
    {

        $frontend = $this->frontendRoute();

        if ($frontend && file_exists($frontend)) {

            $router->group([
                'namespace'  => config($this->configName.'.core.frontend.namespace'),
                'prefix'     => config($this->configName.'.core.frontend.route-prefix'),
            ], function (Router $router) use ($frontend) {
                require $frontend;
            });
        }

    }

    /**
     * @param Router $router
     */
    private function loadBackendRoutes(Router $router)
    {
        $backend = $this->backendRoute();

        if ($backend && file_exists($backend)) {
            $router->group([
                'namespace'  => config($this->configName.'.core.backend.namespace'),
                'prefix'     => config($this->configName.'.core.backend.route-prefix'),
            ], function (Router $router) use ($backend) {
                require $backend;
            });
        }
    }

    /**
     * @param Router $router
     */
    private function loadApiRoutes(Router $router)
    {
        $api = $this->apiRoute();

        if ($api && file_exists($api)) {

            $version = config($this->configName.'.core.api_current_version', ['V1']);
            $prefixName = strtolower($version);

            // // bind id.
            // $router->bind('id', function ($id) {
            //     return Hasher::decode($id);
            // });

            $router->group([
                'namespace' => 'Api\\'.strtoupper($version),
                'prefix' => 'api/'.$prefixName,
                'middleware' => config($this->configName.'.core.middleware.api', []),
            ], function (Router $router) use ($api) {
                require $api;
            });
        }
    }
}