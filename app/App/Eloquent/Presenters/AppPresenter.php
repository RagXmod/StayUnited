<?php

namespace App\App\Eloquent\Presenters;

use App\App\Eloquent\Transformers\AppTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AppPresenter.
 *
 * @package namespace App\App\Eloquent\Presenters;
 */
class AppPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AppTransformer();
    }
}
