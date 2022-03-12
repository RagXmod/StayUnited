<?php

namespace Modules\Category\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Modules\Category\Eloquent\Interfaces\CategoryRepository;
use Modules\Category\Eloquent\Entities\Category;
use Modules\Category\Eloquent\Validators\CategoryValidator;

/**
 * Class CategoryRepositoryEloquent.
 *
 * @package namespace Modules\Category\Eloquent\Repositories;
 */
class CategoryRepositoryEloquent extends BaseRepository implements CategoryRepository
{
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


    public function getAllParentCategories( $limit = 10) {

        $parentCategories = $this->model->isActive()->where('parent_id', null)->get()->take(2);
        if ( $parentCategories->isEmpty() ) return [];

        $ids             = array_pluck($parentCategories, ['id']);


        $childCategories = $this->model->isActive()->whereIn('parent_id', $ids)->get();
        if ( $childCategories->isEmpty() )
            return [];

        $items = $parentCategories->each(function($item) use($childCategories, $limit) {
                        $_child = $childCategories->where('parent_id', $item->id)->take($limit);
                        if ( $_child )
                            $item->child_categories = $_child->toArray();
                })->toArray();

        return $items;

    }


    public function findBySlug( $slug ) {
       return $this->model->with('apps')->where('slug', $slug)->first();
    }

}
