<?php

namespace App\App\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Interfaces\AppMoreDetailRepository;
use App\App\Eloquent\Entities\AppMoreDetail;
use App\App\Eloquent\Validators\AppMoreDetailValidator;

/**
 * Class AppMoreDetailRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class AppMoreDetailRepositoryEloquent extends BaseRepository implements AppMoreDetailRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AppMoreDetail::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
