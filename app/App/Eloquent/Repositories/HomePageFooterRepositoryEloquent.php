<?php

namespace App\App\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Interfaces\HomePageFooterRepository;
use App\App\Eloquent\Entities\HomePageFooter;
use App\App\Eloquent\Validators\HomePageFooterValidator;

/**
 * Class HomePageFooterRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class HomePageFooterRepositoryEloquent extends BaseRepository implements HomePageFooterRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return HomePageFooter::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function menus() {

        $statusKey   = $this->model->cacheKeyArray('tbl_name');
        $that        = $this;

        $menus = cache()->rememberForever( $statusKey, function () use( $that ) {
            $categories =  $that->model->defaultOrder()->get()->toTree()->toArray();
            return array_filter(array_map('array_filter', $categories));

        });
        return $menus;

    }
}
