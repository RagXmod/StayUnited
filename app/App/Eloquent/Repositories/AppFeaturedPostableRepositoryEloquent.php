<?php

namespace App\App\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Interfaces\AppFeaturedPostableRepository;
use App\App\Eloquent\Entities\AppFeaturedPostable;
use App\App\Eloquent\Validators\AppFeaturedPostableValidator;

/**
 * Class AppFeaturedPostableRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class AppFeaturedPostableRepositoryEloquent extends BaseRepository implements AppFeaturedPostableRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AppFeaturedPostable::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
