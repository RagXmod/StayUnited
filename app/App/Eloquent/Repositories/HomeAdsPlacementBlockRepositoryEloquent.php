<?php

namespace App\App\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\Core\Traits\RepositoryEloquentTrait;
use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Entities\HomeAdsPlacementBlock;
use App\App\Eloquent\Interfaces\HomeAdsPlacementBlockRepository;

/**
 * Class HomeAdsPlacementBlockRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class HomeAdsPlacementBlockRepositoryEloquent extends BaseRepository implements HomeAdsPlacementBlockRepository
{
    use RepositoryEloquentTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return HomeAdsPlacementBlock::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function adsPlacementWithChildrenCollections() {
        $items = $this->model->with('ads')->get();
        return $items;
    }

    public function showAds() {

        $statusKey   = $this->model->cacheKeyArray('ads_collections');

        $that        = $this;

        $collections = cache()->remember( $statusKey, 1440, function () use( $that ) {
            $_item = $that->adsPlacementWithChildrenCollections();
            $_itemArray = [];
            foreach($_item as $item) {
                $_itemArray[$item->identifier] = $item->ads->toArray();
            }
            return $_itemArray;
        });

        return $collections;
    }

    public function adsPlacementCollections() {
        return $this->cachemodel();
    }

}
