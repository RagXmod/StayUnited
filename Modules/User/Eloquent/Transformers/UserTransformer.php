<?php

namespace Modules\User\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\User\Eloquent\Entities\User;

/**
 * Class UserTransformer.
 *
 * @package namespace Modules\User\Eloquent\Transformers;
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * Transform the User entity.
     *
     * @param \Modules\User\Eloquent\Entities\User $model
     *
     * @return array
     */
    public function transform(User $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
