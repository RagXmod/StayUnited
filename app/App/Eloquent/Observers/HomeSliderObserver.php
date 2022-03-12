<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\HomeSlider;
use Modules\Core\Eloquents\Observers\BaseModelObserver;
use Exception;
use Illuminate\Support\Facades\Storage;
class HomeSliderObserver extends BaseModelObserver
{
    public function __construct() {

        $config = app(HomeSlider::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $config->cacheKeyArray())
                                );
    }

    public function deleted($model) {

        try {

            $storage  =  Storage::disk('slider-uploads');

            if (  $storage->exists( $model->path ) )
                $storage->delete($model->path);


            parent::deleted($model);

        } catch (Exception $e) {
            logger()->debug($e);
        }
    }
}
