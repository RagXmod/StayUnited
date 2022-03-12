<?php

namespace App\App\Eloquent\Repositories;

use App\App\Eloquent\Entities\Advertisement;
use Prettus\Repository\Eloquent\BaseRepository;
use Modules\Core\Traits\RepositoryEloquentTrait;
use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Validators\AdvertisementValidator;
use App\App\Eloquent\Interfaces\AdvertisementRepository;

/**
 * Class AdvertisementRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class AdvertisementRepositoryEloquent extends BaseRepository implements AdvertisementRepository
{

    use RepositoryEloquentTrait;

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



    /**
     * Boot up the repository, pushing criteria
     */
    public function adsCollections()
    {
        return $this->cachemodel();
    }

}
