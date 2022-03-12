<?php

namespace Modules\Advertisement\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Modules\Advertisement\Eloquent\Interfaces\AdvertisementRepository;
use Modules\Advertisement\Eloquent\Entities\Advertisement;
use Modules\Advertisement\Eloquent\Validators\AdvertisementValidator;

/**
 * Class AdvertisementRepositoryEloquent.
 *
 * @package namespace Modules\Advertisement\Eloquent\Repositories;
 */
class AdvertisementRepositoryEloquent extends BaseRepository implements AdvertisementRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Advertisement::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
