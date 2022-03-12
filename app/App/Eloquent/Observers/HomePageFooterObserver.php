<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\HomePageFooter;
use Modules\Core\Eloquents\Observers\BaseModelObserver;

class HomePageFooterObserver extends BaseModelObserver
{
    public function __construct() {

        $config = app(HomePageFooter::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $config->cacheKeyArray())
                                );
    }
}
