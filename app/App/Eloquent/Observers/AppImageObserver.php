<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\AppImage;
use Modules\Core\Eloquents\Observers\BaseModelObserver;
use Storage;

class AppImageObserver extends BaseModelObserver
{
    public function __construct() {

        $config = app(AppImage::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $config->cacheKeyArray())
                                );
    }



    public function deleting(AppImage $model) {
        try {
            Storage::disk('apk-uploads')->delete( $model->file_path );
        } catch (Exception $e) {
            logger()->debug($e);
        }

    }


}
