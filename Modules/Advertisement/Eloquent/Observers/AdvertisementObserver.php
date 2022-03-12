<?php

namespace Modules\Advertisement\Eloquent\Observers;

use App\App\Eloquent\Entities\Advertisement;
use App\App\Eloquent\Entities\HomeAdsPlacementBlock;
use App\App\Eloquent\Entities\HomeAdsPlacementBlockable;
use Modules\Core\Eloquents\Observers\BaseModelObserver;
use Modules\Advertisement\Eloquent\Entities\Advertisement as ModuleAdvertisement;

class AdvertisementObserver extends BaseModelObserver
{
    public function __construct() {

        $model            = app(Advertisement::class);
        $moduleAds        = app(ModuleAdvertisement::class);
        $homeAds          = app(HomeAdsPlacementBlock::class);
        $homeAdsBlockable = app(HomeAdsPlacementBlockable::class);

        $_flattenModel       = array_flatten($model->cacheKeyArray());
        $_flattenModuleAds   = array_flatten($moduleAds->cacheKeyArray());
        $_flattenHomeHomeAds = array_flatten($homeAds->cacheKeyArray());
        $_flattenHomeAdsBlockable = array_flatten($homeAdsBlockable->cacheKeyArray());

        $this->pushNewTableCacheName(
                                array_merge([

                                ], $_flattenModel,
                                    $_flattenModuleAds,
                                    $_flattenHomeHomeAds,
                                    $_flattenHomeAdsBlockable
                                )
                                );
    }

}