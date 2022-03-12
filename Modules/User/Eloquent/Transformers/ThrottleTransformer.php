<?php

namespace Modules\User\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\User\Eloquent\Entities\Throttle;

/**
 * Class ThrottleTransformer.
 *
 * @package namespace Modules\User\Eloquent\Transformers;
 */
class ThrottleTransformer extends TransformerAbstract
{
    /**
     * Transform the Throttle entity.
     *
     * @param \Modules\User\Eloquent\Entities\Throttle $model
     *
     * @return array
     */
    public function transform(Throttle $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
