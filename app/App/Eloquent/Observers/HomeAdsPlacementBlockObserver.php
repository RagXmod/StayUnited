<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\Advertisement;
use App\App\Eloquent\Entities\HomeAdsPlacementBlock;
use Modules\Core\Eloquents\Observers\BaseModelObserver;

class HomeAdsPlacementBlockObserver extends BaseModelObserver
{
    public function __construct() {

        $config = app(HomeAdsPlacementBlock::class);
        $ads    = app(Advertisement::class);


        $_flattenConfig  = array_flatten($config->cacheKeyArray());
        $_flattenAds     = array_flatten($ads->cacheKeyArray());

        $this->pushNewTableCacheName(
                                array_merge([

                                ], $_flattenConfig, $_flattenAds)
                                );
    }
}
