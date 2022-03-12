<?php

namespace App\App\Eloquent\Observers;

use App\App\Eloquent\Entities\AppVersion;
use App\App\Eloquent\Entities\App;
use Modules\Core\Eloquents\Observers\BaseModelObserver;
use Modules\Configuration\Eloquent\Entities\Configuration;
use Storage;

class AppVersionObserver extends BaseModelObserver
{
    public function __construct() {

        $config = app(Configuration::class);
        $this->pushNewTableCacheName(
                                array_merge([

                                ], $config->cacheKeyArray())
                                );
    }

    public function deleting(AppVersion $model) {

        try {

            $storage  =  Storage::disk('apk-uploads');
            if (  $storage->exists( $model->file_path ) ) {

                $dirname = pathinfo( $model->file_path, PATHINFO_DIRNAME);
                Storage::disk('apk-uploads')->deleteDirectory( $dirname );
            }


        } catch (Exception $e) {
            logger()->debug($e);
        }
    }


    public function saved($model) {

        if ( $model->app ) {

            $appModel = app(App::class);
            $appDetailKey = $appModel->cacheKeyArray('app_identifier_key') . $model->app->slug;
            array_push($this->additionalKeyArr, $appDetailKey);
        }
        parent::saved($model);
    }
}
