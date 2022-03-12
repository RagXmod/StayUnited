<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\HomePageMenu;
use Modules\Core\Eloquents\Observers\BaseModelObserver;

class HomePageMenuObserver extends BaseModelObserver
{
    public function __construct() {

        $config = app(HomePageMenu::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $config->cacheKeyArray())
                                );
    }
}
