<?php

namespace Modules\User\Eloquent\Observers;

use Cache;
use Modules\User\Eloquent\Entities\Throttle;

use Modules\Core\Traits\ModelObserverTrait;

class ThrottleObserver
{

    use ModelObserverTrait;

    /**
     * Listen to the Throttle created event.
     *
     * @param  \Modules\User\Eloquent\Entities\Throttle  $model
     * @return void
     */
    public function created(Throttle $model)
    {
         $this->removeArrayKeys( $model );
    }

    /**
     * Listen to the Throttle saved event.
     *
     * @param  \Modules\User\Eloquent\Entities\Throttle  $model
     * @return void
     */
    public function saved(Throttle $model)
    {
         $this->removeArrayKeys( $model );
    }


    /**
     * Listen to the Throttle deleted event.
     *
     * @param  \Modules\User\Eloquent\Entities\Throttle  $model
     * @return void
     */
    public function deleted(Throttle $model)
    {
         $this->removeArrayKeys( $model );

    }

    public function removeArrayKeys(Throttle $model ) {

        $arrItems = array_merge($model->cacheKeyArray(),[

            // custom cache name here for throttle.

        ]);

        foreach($arrItems as $item) {
            $this->removeCache( $item );
        }
    }

}