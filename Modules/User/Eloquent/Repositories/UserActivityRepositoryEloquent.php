<?php

namespace Modules\User\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\User\Eloquent\Entities\UserActivity;
use Prettus\Repository\Criteria\RequestCriteria;
use Modules\User\Eloquent\Observers\UserActivityObserver;
use Modules\User\Eloquent\Validators\UserActivityValidator;
use Modules\User\Eloquent\Interfaces\UserActivityRepository;

/**
 * Class UserActivityRepositoryEloquent.
 *
 * @package namespace Modules\User\Eloquent\Repositories;
 */
class UserActivityRepositoryEloquent extends BaseRepository implements UserActivityRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserActivity::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));

        UserActivity::observe(new UserActivityObserver());
    }

}
