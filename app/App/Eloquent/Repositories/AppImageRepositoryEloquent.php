<?php

namespace App\App\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Interfaces\AppImageRepository;
use App\App\Eloquent\Entities\AppImage;
use App\App\Eloquent\Validators\AppImageValidator;

/**
 * Class AppImageRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class AppImageRepositoryEloquent extends BaseRepository implements AppImageRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AppImage::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
