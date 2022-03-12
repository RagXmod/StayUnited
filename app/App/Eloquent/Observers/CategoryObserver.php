<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\Category;
use Modules\Core\Eloquents\Observers\BaseModelObserver;

class CategoryObserver extends BaseModelObserver
{
    public function __construct() {

        $config = app(Category::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $config->cacheKeyArray())
                                );
    }
}
