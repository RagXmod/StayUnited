<?php

namespace Modules\Page\Eloquent\Observers;

use Modules\Page\Eloquent\Entities\Page;
use Modules\Core\Eloquents\Observers\BaseModelObserver;

class PageObserver extends BaseModelObserver
{
    public function __construct() {

        $page = app(Page::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $page->cacheKeyArray())
                                );
    }

}