<?php

namespace App\App\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use App\App\Eloquent\Entities\Categoreable;

/**
 * Class CategoreableTransformer.
 *
 * @package namespace App\App\Eloquent\Transformers;
 */
class CategoreableTransformer extends TransformerAbstract
{
    /**
     * Transform the Categoreable entity.
     *
     * @param \App\App\Eloquent\Entities\Categoreable $model
     *
     * @return array
     */
    public function transform(Categoreable $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
