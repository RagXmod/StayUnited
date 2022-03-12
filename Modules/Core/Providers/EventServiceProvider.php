<?php

namespace Modules\Core\Providers;

use Exception;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
   /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [

    ];

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // if demo mode... dont allow to save and delete..
        if ( env('DEMO_MODE_ON', false) === true ) {

            Event::listen('eloquent.saving: *', function ( $model ) {

                if ( str_contains($model, ['User','EloquentViewable']))
                    return true;
                else {
                    logger()->debug($model);
                    throw new Exception('System is in demo mode, you cannot create, update or delete an existing item.');
                }

            });

            Event::listen('eloquent.deleting: *', function ($model) {

                if ( str_contains($model, ['User','EloquentViewable']))
                    return true;
                else {
                    logger()->debug($model);
                    throw new Exception('System is in demo mode, permission not granted.');
                }

            });
        }

    }
}
