<?php

namespace App\App\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use App\App\Eloquent\Entities\AppVersion;

/**
 * Class AppVersionTransformer.
 *
 * @package namespace App\App\Eloquent\Transformers;
 */
class AppVersionTransformer extends TransformerAbstract
{
    /**
     * Transform the AppVersion entity.
     *
     * @param \App\App\Eloquent\Entities\AppVersion $model
     *
     * @return array
     */
    public function transform(AppVersion $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
