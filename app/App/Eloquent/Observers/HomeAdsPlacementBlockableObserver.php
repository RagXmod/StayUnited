<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\Advertisement;
use App\App\Eloquent\Entities\HomeAdsPlacementBlock;
use App\App\Eloquent\Entities\HomeAdsPlacementBlockable;
use Modules\Core\Eloquents\Observers\BaseModelObserver;

class HomeAdsPlacementBlockableObserver extends BaseModelObserver
{
    public function __construct() {

        $config  = app(HomeAdsPlacementBlockable::class);
        $homeAds = app(HomeAdsPlacementBlock::class);
        $ads     = app(Advertisement::class);


        $_flattenConfig            = array_flatten($config->cacheKeyArray());
        $_flattenHomeAds          = array_flatten($homeAds->cacheKeyArray());
        $_flattenAds = array_flatten($ads->cacheKeyArray());

        $this->pushNewTableCacheName(
                                array_merge([

                                ], $_flattenConfig,
                                  $_flattenHomeAds,
                                  $_flattenAds)
                                );
    }
}
