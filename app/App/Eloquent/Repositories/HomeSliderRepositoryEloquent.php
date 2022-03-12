<?php

namespace App\App\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Interfaces\HomeSliderRepository;
use App\App\Eloquent\Entities\HomeSlider;
use App\App\Eloquent\Validators\HomeSliderValidator;

/**
 * Class HomeSliderRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class HomeSliderRepositoryEloquent extends BaseRepository implements HomeSliderRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return HomeSlider::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function sliders() {

        $statusKey   = $this->model->cacheKeyArray('tbl_name');
        $that        = $this;

        // $sliders = $that->model->orderBy('position','asc')->get()->toArray();

        $sliders = cache()->rememberForever( $statusKey, function () use( $that ) {
            return $that->model->orderBy('position','asc')->get()->toArray();
        });
        return $sliders;

    }

}
