<?php

namespace App\App\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use App\App\Eloquent\Entities\AppDeveloper;

/**
 * Class AppDeveloperTransformer.
 *
 * @package namespace App\App\Eloquent\Transformers;
 */
class AppDeveloperTransformer extends TransformerAbstract
{
    /**
     * Transform the AppDeveloper entity.
     *
     * @param \App\App\Eloquent\Entities\AppDeveloper $model
     *
     * @return array
     */
    public function transform(AppDeveloper $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
