<?php

namespace App\App\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;

/**
 * Class ApiSearchTransformer.
 *
 * @package namespace App\App\Eloquent\Transformers;
 */
class ApiSearchTransformer extends TransformerAbstract
{
    /**
     * Transform the App entity.
     *
     * @param \App\App\Eloquent\Entities\App $model
     *
     * @return array
     */
    public function transform( $model )
    {
        return [
            'id' => 'aa'
        ];
    }
}