<?php

namespace Modules\User\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\User\Eloquent\Entities\UserUpload;

/**
 * Class UserUploadTransformer.
 *
 * @package namespace Modules\User\Eloquent\Transformers;
 */
class UserUploadTransformer extends TransformerAbstract
{
    /**
     * Transform the UserUpload entity.
     *
     * @param \Modules\User\Eloquent\Entities\UserUpload $model
     *
     * @return array
     */
    public function transform(UserUpload $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
