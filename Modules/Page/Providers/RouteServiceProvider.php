<?php

namespace Modules\Page\Providers;

use Modules\Core\Providers\RoutingServiceProvider as CoreRoutingServiceProvider;

class RouteServiceProvider extends CoreRoutingServiceProvider
{
     /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $namespace = 'Modules\Page\Http\Controllers';

    /**
     * @return string
     */
    protected function frontendRoute()
    {
        return false;
    }

    /**
     * @return string
     */
    protected function backendRoute()
    {
        return __DIR__ . '/../Routes/admin.php';
    }

    /**
     * @return string
     */
    protected function apiRoute()
    {
        return false;
    }
}
