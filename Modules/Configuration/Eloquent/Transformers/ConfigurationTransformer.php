<?php

namespace Modules\Configuration\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Configuration\Eloquent\Entities\Configuration;

/**
 * Class ConfigurationTransformer.
 *
 * @package namespace Modules\Configuration\Eloquent\Transformers;
 */
class ConfigurationTransformer extends TransformerAbstract
{
    /**
     * Transform the Configuration entity.
     *
     * @param \Modules\Configuration\Eloquent\Entities\Configuration $model
     *
     * @return array
     */
    public function transform(Configuration $model)
    {

        $value = $model->value;
        if ( $model->identifier === 'site_logo')  {
            $value = ( $model->value ) ? asset($model->value) : 'https://via.placeholder.com/350x150.png';
        }
        return [

            'group'       => $model->group,
            'identifier'  => $model->identifier,
            'value'       => $value,
            'description' => $model->description
        ];
    }
}
