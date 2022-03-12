<?php

namespace App\App\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Interfaces\CategoreableRepository;
use App\App\Eloquent\Entities\Categoreable;
use App\App\Eloquent\Validators\CategoreableValidator;

/**
 * Class CategoreableRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class CategoreableRepositoryEloquent extends BaseRepository implements CategoreableRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Categoreable::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
