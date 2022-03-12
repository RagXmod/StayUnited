<?php

namespace Modules\User\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\User\Eloquent\Entities\UserActivity;

/**
 * Class UserActivityTransformer.
 *
 * @package namespace Modules\User\Eloquent\Transformers;
 */
class UserActivityTransformer extends TransformerAbstract
{
    /**
     * Transform the UserActivity entity.
     *
     * @param \Modules\User\Eloquent\Entities\UserActivity $model
     *
     * @return array
     */
    public function transform(UserActivity $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
