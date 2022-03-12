<?php

namespace Modules\User\Providers;

use Sentinel;
use Validator;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    //  /**
    //  * @var array
    //  */
    // protected $middleware = [
    //     'dcm.logged.in'       => \Modules\User\Http\Middleware\SentinelFrontend::class,
    //     'dcm-admin.logged.in' => \Modules\User\Http\Middleware\SentinelBackend::class,
    // ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->registerCommands();
        $this->registerMiddleware();
        $this->registerRules();
        $this->registerHttps();

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->registerBladeDirectives();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {


        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('user.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../Config/auth.php' => config_path('auth.php'),
        ], 'auth');

        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'user'
        );

        $this->mergeConfigFrom(
            __DIR__.'/../Config/auth.php', 'auth'
        );
    }



    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/user');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/user';
        }, \Config::get('view.paths')), [$sourcePath]), 'user');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/user');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'user');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'user');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
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

    public function registerCommands()
    {
        // $commands = [

        // ];
        // $this->commands($commands);
    }

    public function registerMiddleware()
    {
        $middleware =  [
            config('user.web_middleware_name')   => \Modules\User\Http\Middleware\SentinelFrontend::class,
            config('user.admin_middleware_name') => \Modules\User\Http\Middleware\SentinelBackend::class,
        ];

        foreach ($middleware as $name => $class) {
            $this->app['router']->aliasMiddleware($name, $class);
        }
    }

    private function registerBindings()
    {
        $driver = config('user.auth.driver');


        $this->app->bind(
            \Modules\User\Contracts\Authentication::class,
            "Modules\\User\\Eloquent\\Repositories\\Authentication\\SentinelAuth"
        );

        // Custom Checkpoints
        Sentinel::addCheckpoint('suspension', app(\Modules\User\Checkpoints\Suspension\SuspensionCheckpoint::class));
        Sentinel::addCheckpoint('ban', app(\Modules\User\Checkpoints\Ban\BanCheckpoint::class));
        Sentinel::addCheckpoint('throttle', app(\Modules\User\Checkpoints\Throttling\ThrottleCheckpoint::class));

    }

    private function registerBladeDirectives() {

        $auth = app(\Modules\User\Contracts\Authentication::class);
        Blade::if('hasaccess', function ($permission)  use($auth){
            return $auth->user()->hasAccess($permission);
        });

        Blade::if('hasanyaccess', function ($permission)  use($auth){
            return $auth->user()->hasAnyAccess($permission);
        });

    }


    public function registerRules() {

        Validator::extend(
            'recaptcha',
            'Modules\User\Rules\ReCaptcha'
        );
    }


    private function registerHttps() {

        if( dcmConfig('enable_ssl') === 'yes' )
            app(UrlGenerator::class)->forceScheme('https');
    }
}
