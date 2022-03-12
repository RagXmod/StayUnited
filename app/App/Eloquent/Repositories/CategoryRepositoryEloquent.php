<?php

namespace App\App\Eloquent\Repositories;

use App\App\Eloquent\Entities\Category;
use Prettus\Repository\Eloquent\BaseRepository;
use Modules\Core\Traits\RepositoryEloquentTrait;
use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Interfaces\CategoryRepository;

/**
 * Class CategoryRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class CategoryRepositoryEloquent extends BaseRepository implements CategoryRepository
{

    use RepositoryEloquentTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function categoryOptionsForApps()
    {

        $cacheKey = $this->model->cacheKeyArray('category_app_options_cache_key');
        $that = $this;
        $collections = cache()->rememberForever( $cacheKey, function () use( $that ) {
            $_collections = $that->categoryCollections  ();
            $cacheModel = $_collections->map(function( $item, $index ){
                return [
                    'id'    => $item['id'],
                    'label'    => $item['title'],
                    'value'    => $item['identifier']
                ];
            });
            return $cacheModel;
        });


        return $collections;
    }


     /**
     * Boot up the repository, pushing criteria
     */
    public function categoryCollections()
    {
        // $all = $this->model->all();
        return $this->cachemodel();
    }
}
