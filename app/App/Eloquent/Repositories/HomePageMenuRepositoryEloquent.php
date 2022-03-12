<?php

namespace App\App\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Interfaces\HomePageMenuRepository;
use App\App\Eloquent\Entities\HomePageMenu;
use App\App\Eloquent\Validators\HomePageMenuValidator;

/**
 * Class HomePageMenuRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class HomePageMenuRepositoryEloquent extends BaseRepository implements HomePageMenuRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return HomePageMenu::class;
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
