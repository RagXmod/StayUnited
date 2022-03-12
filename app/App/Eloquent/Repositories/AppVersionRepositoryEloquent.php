<?php

namespace App\App\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Interfaces\AppVersionRepository;
use App\App\Eloquent\Entities\AppVersion;

/**
 * Class AppVersionRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class AppVersionRepositoryEloquent extends BaseRepository implements AppVersionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AppVersion::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
