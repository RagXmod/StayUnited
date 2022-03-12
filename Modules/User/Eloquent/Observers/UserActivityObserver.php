<?php

namespace Modules\User\Eloquent\Observers;

use Cache;
use Modules\User\Eloquent\Entities\UserActivity;

class UserActivityObserver
{

    /**
     * Listen to the UserActivity created event.
     *
     * @param  \Modules\User\Eloquent\Entities\UserActivity  $model
     * @return void
     */
    public function created(UserActivity $model)
    {
        // Cache::tags($model->getTable())->flush();
    }

    /**
     * Listen to the UserActivity saved event.
     *
     * @param  \Modules\User\Eloquent\Entities\UserActivity  $model
     * @return void
     */
    public function saved(UserActivity $model)
    {
        // Cache::tags($model->getTable())->flush();
    }


    /**
     * Listen to the UserActivity deleted event.
     *
     * @param  \Modules\User\Eloquent\Entities\UserActivity  $model
     * @return void
     */
    public function deleted(UserActivity $model)
    {
        // Cache::tags($model->getTable())->flush();
    }

}