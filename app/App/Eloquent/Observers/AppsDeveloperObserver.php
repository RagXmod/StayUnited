<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\AppsDeveloper;
use Modules\Core\Eloquents\Observers\BaseModelObserver;

class AppsDeveloperObserver extends BaseModelObserver
{
    public function __construct() {

        $config = app(AppsDeveloper::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $config->cacheKeyArray())
                                );
    }
}
