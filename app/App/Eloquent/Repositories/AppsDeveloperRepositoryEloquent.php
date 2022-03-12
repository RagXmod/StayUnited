<?php

namespace App\App\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Interfaces\AppsDeveloperRepository;
use App\App\Eloquent\Entities\AppsDeveloper;
use App\App\Eloquent\Validators\AppsDeveloperValidator;

/**
 * Class AppsDeveloperRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class AppsDeveloperRepositoryEloquent extends BaseRepository implements AppsDeveloperRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AppsDeveloper::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
