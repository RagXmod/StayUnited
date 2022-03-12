<?php

namespace Modules\Configuration\Eloquent\Presenters;

use Modules\Configuration\Eloquent\Transformers\ConfigurationTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ConfigurationPresenter.
 *
 * @package namespace Modules\Configuration\Eloquent\Presenters;
 */
class ConfigurationPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ConfigurationTransformer();
    }
}
