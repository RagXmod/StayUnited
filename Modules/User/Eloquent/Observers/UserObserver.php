<?php

namespace Modules\User\Eloquent\Observers;

use Modules\User\Eloquent\Entities\User;
use Modules\User\Eloquent\Entities\UserUpload;
use Modules\User\Eloquent\Entities\Throttle;



use Modules\Core\Eloquents\Observers\BaseModelObserver;

class UserObserver extends BaseModelObserver
{
    public function __construct() {

        $user       = app(User::class);
        $userUpload = app(UserUpload::class);
        $throttle   = app(Throttle::class);

        $arrItems = [
            $userUpload->uploadTypeAvatar(),
            $userUpload->uploadTypeGravatar(),
            $userUpload->uploadTypeInitials()
        ];

        $this->pushNewTableCacheName(
                                array_merge(
                                    $arrItems, $user->cacheKeyArray()
                                    )
                                );
    }

}