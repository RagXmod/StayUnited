<?php

namespace Modules\User\Providers;

/**
 * Module Template: Modules\User\Providers\ComposerServiceProvider
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ComposerServiceProvider extends ServiceProvider

{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // common for all views
        view()->composer(
            '*', 'Modules\User\ViewComposers\User'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}