<?php

namespace Modules\User\Eloquent\Observers;

use Cache;
use Modules\User\Eloquent\Entities\UserUpload;

use Modules\Core\Traits\ModelObserverTrait;

class UserUploadObserver
{

    use ModelObserverTrait;

    /**
     * Listen to the UserUpload created event.
     *
     * @param  \Modules\User\Eloquent\Entities\UserUpload  $model
     * @return void
     */
    public function created(UserUpload $model)
    {
         $this->removeArrayKeys( $model );
    }

    /**
     * Listen to the UserUpload saved event.
     *
     * @param  \Modules\User\Eloquent\Entities\UserUpload  $model
     * @return void
     */
    public function saved(UserUpload $model)
    {
         $this->removeArrayKeys( $model );
    }


    /**
     * Listen to the UserUpload deleted event.
     *
     * @param  \Modules\User\Eloquent\Entities\UserUpload  $model
     * @return void
     */
    public function deleted(UserUpload $model)
    {
         $this->removeArrayKeys( $model );

    }

    public function removeArrayKeys( $model ) {


        $arrItems = [

            $model->uploadTypeAvatar(),
            $model->uploadTypeGravatar(),
            $model->uploadTypeInitials()
        ];

        foreach($arrItems as $item) {
            $this->removeCache( $item );
        }
    }

}