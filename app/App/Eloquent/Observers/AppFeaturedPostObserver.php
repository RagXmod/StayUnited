<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\AppFeaturedPost;
use Modules\Core\Eloquents\Observers\BaseModelObserver;

class AppFeaturedPostObserver extends BaseModelObserver
{
    public function __construct() {

        $model = app(AppFeaturedPost::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $model->cacheKeyArray())
                                );
    }
}
