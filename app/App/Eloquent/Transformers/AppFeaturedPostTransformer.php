<?php

namespace App\App\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use App\App\Eloquent\Entities\AppFeaturedPost;

/**
 * Class AppFeaturedPostTransformer.
 *
 * @package namespace App\App\Eloquent\Transformers;
 */
class AppFeaturedPostTransformer extends TransformerAbstract
{
    /**
     * Transform the AppFeaturedPost entity.
     *
     * @param \App\App\Eloquent\Entities\AppFeaturedPost $model
     *
     * @return array
     */
    public function transform(AppFeaturedPost $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
