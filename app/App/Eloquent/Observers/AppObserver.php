<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\App;
use Modules\Core\Eloquents\Observers\BaseModelObserver;
use Modules\Configuration\Eloquent\Entities\Configuration;

class AppObserver extends BaseModelObserver
{
    public function __construct() {

        $config = app(Configuration::class);
        $model  = app(App::class);


        $this->pushNewTableCacheName(
                                array_merge([

                                ], $config->cacheKeyArray(), $model->cacheKeyArray())
                                );
    }


     /*
     *
    */
    public function saved($model)
    {

        if ( isset($this->additionalKeyArr['app_identifier_key'] )) {
            $modelCacheName = $this->additionalKeyArr['app_identifier_key'].$model->slug;
            $this->additionalKeyArr['app_identifier_key'] = $modelCacheName;
        }
        parent::saved($model);
    }
}
