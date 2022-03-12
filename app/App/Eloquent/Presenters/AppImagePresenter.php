<?php

namespace App\App\Eloquent\Presenters;

use App\App\Eloquent\Transformers\AppImageTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AppImagePresenter.
 *
 * @package namespace App\App\Eloquent\Presenters;
 */
class AppImagePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AppImageTransformer();
    }
}
