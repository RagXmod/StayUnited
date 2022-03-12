<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\AppMoreDetail;
use Modules\Core\Eloquents\Observers\BaseModelObserver;

class AppMoreDetailObserver extends BaseModelObserver
{
    public function __construct() {

        $model = app(AppMoreDetail::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $model->cacheKeyArray())
                                );
    }
}
