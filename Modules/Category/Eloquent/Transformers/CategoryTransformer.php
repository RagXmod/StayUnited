<?php

namespace Modules\Category\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Category\Eloquent\Entities\Category;

/**
 * Class CategoryTransformer.
 *
 * @package namespace Modules\Category\Eloquent\Transformers;
 */
class CategoryTransformer extends TransformerAbstract
{
    /**
     * Transform the Category entity.
     *
     * @param \Modules\Category\Eloquent\Entities\Category $model
     *
     * @return array
     */
    public function transform(Category $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
