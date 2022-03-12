<?php

namespace Modules\Configuration\Eloquent\Observers;

use Cache;
use Modules\Configuration\Eloquent\Entities\Configuration;
use Modules\Core\Eloquents\Observers\BaseModelObserver;

class ConfigurationObserver extends BaseModelObserver
{
    public function __construct() {

        $config = app(Configuration::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $config->cacheKeyArray())
                                );
    }
}
