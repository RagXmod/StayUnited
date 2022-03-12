<?php

namespace Modules\User\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\User\Eloquent\Entities\UserDetail;

/**
 * Class UserDetailTransformer.
 *
 * @package namespace Modules\User\Eloquent\Transformers;
 */
class UserDetailTransformer extends TransformerAbstract
{
    /**
     * Transform the UserDetail entity.
     *
     * @param \Modules\User\Eloquent\Entities\UserDetail $model
     *
     * @return array
     */
    public function transform(UserDetail $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
