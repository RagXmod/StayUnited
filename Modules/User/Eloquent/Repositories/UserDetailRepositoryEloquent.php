<?php

namespace Modules\User\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Modules\User\Eloquent\Interfaces\UserDetailRepository;
use Modules\User\Eloquent\Entities\UserDetail;
use Modules\User\Eloquent\Validators\UserDetailValidator;

/**
 * Class UserDetailRepositoryEloquent.
 *
 * @package namespace Modules\User\Eloquent\Repositories;
 */
class UserDetailRepositoryEloquent extends BaseRepository implements UserDetailRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserDetail::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
