<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\Advertisement;
use App\App\Eloquent\Entities\HomeAdsPlacementBlock;
use Modules\Core\Eloquents\Observers\BaseModelObserver;
use App\App\Eloquent\Entities\HomeAdsPlacementBlockable;

class AdvertisementObserver extends BaseModelObserver
{
    public function __construct() {

        $model = app(Advertisement::class);
        $homeAds = app(HomeAdsPlacementBlock::class);
        $homeAdsBlockable = app(HomeAdsPlacementBlockable::class);

        $_flattenModel            = array_flatten($model->cacheKeyArray());
        $_flattenHomeAds          = array_flatten($homeAds->cacheKeyArray());
        $_flattenHomeAdsBlockable = array_flatten($homeAdsBlockable->cacheKeyArray());

        $this->pushNewTableCacheName(
                                array_merge([

                                ], $_flattenModel, $_flattenHomeAds, $_flattenHomeAdsBlockable)
                                );


    }

}