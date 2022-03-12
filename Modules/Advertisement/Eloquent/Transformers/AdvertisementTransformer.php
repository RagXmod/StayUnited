<?php

namespace Modules\Advertisement\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Advertisement\Eloquent\Entities\Advertisement;

/**
 * Class AdvertisementTransformer.
 *
 * @package namespace Modules\Advertisement\Eloquent\Transformers;
 */
class AdvertisementTransformer extends TransformerAbstract
{
    /**
     * Transform the Advertisement entity.
     *
     * @param \Modules\Advertisement\Eloquent\Entities\Advertisement $model
     *
     * @return array
     */
    public function transform(Advertisement $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
