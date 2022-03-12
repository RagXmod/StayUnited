<?php

namespace App\App\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use App\App\Eloquent\Entities\AppImage;

/**
 * Class AppImageTransformer.
 *
 * @package namespace App\App\Eloquent\Transformers;
 */
class AppImageTransformer extends TransformerAbstract
{
    /**
     * Transform the AppImage entity.
     *
     * @param \App\App\Eloquent\Entities\AppImage $model
     *
     * @return array
     */
    public function transform(AppImage $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
