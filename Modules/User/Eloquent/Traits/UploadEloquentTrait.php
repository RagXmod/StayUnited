<?php

namespace Modules\User\Eloquent\Traits;


/**
 * Class UserActivityTransformer.
 *
 * @package namespace Modules\User\Eloquent\Traits;
 */

trait UploadEloquentTrait
{

    // public function uploads()
    // {
    //     return $this->morphMany('Modules\User\Eloquent\Entities\UserUpload', 'uploadable');
    // }

    public function myAvatar()
    {
        return $this->morphOne('Modules\User\Eloquent\Entities\UserUpload', 'uploadable')->where('upload_type','avatar');
    }
}