<?php

namespace Modules\User\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Modules\User\Eloquent\Observers\ThrottleObserver;
use Modules\User\Eloquent\Interfaces\ThrottleRepository;
use Modules\User\Eloquent\Entities\Throttle;

/**
 * Class ThrottleRepositoryEloquent.
 *
 * @package namespace Modules\User\Eloquent\Repositories;
 */
class ThrottleRepositoryEloquent extends BaseRepository implements ThrottleRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Throttle::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
