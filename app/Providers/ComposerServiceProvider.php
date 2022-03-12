<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        try {
            DB::connection()->getPdo();
            if(DB::connection()->getDatabaseName()){
                view()->composer(
                    ['common.footer'], 'App\ViewComposers\FooterComposer'
                );

                view()->composer(
                    ['common.*'], 'App\ViewComposers\MenuComposer'
                );

                view()->composer(
                    ['web.*'], 'App\ViewComposers\SidebarComposer'
                );
            } else{
                abort(404);
            }
        } catch (\Exception $e) {}

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
