<?php

namespace App\App\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use App\App\Eloquent\Entities\AppMoreDetail;

/**
 * Class AppMoreDetailTransformer.
 *
 * @package namespace App\App\Eloquent\Transformers;
 */
class AppMoreDetailTransformer extends TransformerAbstract
{
    /**
     * Transform the AppMoreDetail entity.
     *
     * @param \App\App\Eloquent\Entities\AppMoreDetail $model
     *
     * @return array
     */
    public function transform(AppMoreDetail $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
