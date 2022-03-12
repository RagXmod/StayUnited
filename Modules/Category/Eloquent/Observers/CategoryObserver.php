<?php

namespace Modules\Category\Eloquent\Observers;

use Modules\Category\Eloquent\Entities\Category;
use Modules\Core\Eloquents\Observers\BaseModelObserver;

class CategoryObserver extends BaseModelObserver
{

    private $configModel;

    public function __construct() {

        $this->configModel = app(Category::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $this->configModel->cacheKeyArray())
                                );
    }



    /*
     *
    */
    public function deleted($model)
    {
        $connectedCategories = $this->configModel->where('parent_id', $model->id)->get();
        if ( !$connectedCategories->isEmpty() ) {
            $connectedCategories->each(function($model){
                $model->delete();
            });
        }
        parent::deleted($model);
    }
}
