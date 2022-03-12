<?php

namespace Modules\User\Eloquent\Presenters;

use Modules\User\Eloquent\Transformers\ThrottleTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ThrottlePresenter.
 *
 * @package namespace Modules\User\Eloquent\Presenters;
 */
class ThrottlePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ThrottleTransformer();
    }
}
