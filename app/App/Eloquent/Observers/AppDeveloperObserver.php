<?php

namespace App\App\Eloquent\Observers;

use Modules\Configuration\Eloquent\Entities\Configuration;
use Modules\Core\Eloquents\Observers\BaseModelObserver;

class AppDeveloperObserver extends BaseModelObserver
{
    public function __construct() {

        $config = app(Configuration::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $config->cacheKeyArray())
                                );
    }
}
