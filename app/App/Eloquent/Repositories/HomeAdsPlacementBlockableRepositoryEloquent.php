<?php

namespace App\App\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Interfaces\HomeAdsPlacementBlockableRepository;
use App\App\Eloquent\Entities\HomeAdsPlacementBlockable;
use App\App\Eloquent\Validators\HomeAdsPlacementBlockableValidator;

/**
 * Class HomeAdsPlacementBlockableRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class HomeAdsPlacementBlockableRepositoryEloquent extends BaseRepository implements HomeAdsPlacementBlockableRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return HomeAdsPlacementBlockable::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
