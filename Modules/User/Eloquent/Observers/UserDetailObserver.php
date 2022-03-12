<?php


use Modules\User\Eloquent\Entities\User;
use Modules\User\Eloquent\Entities\UserDetail;
use Modules\Core\Eloquents\Observers\BaseModelObserver;

class PageObserver extends BaseModelObserver
{
    public function __construct() {

        $userDetail = app(UserDetail::class);
        $user = app(User::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $userDetail->cacheKeyArray(),
                                $user->cacheKeyArray())
                                );
    }

}