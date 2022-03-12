<?php

namespace App\App\Eloquent\Presenters;

use App\App\Eloquent\Transformers\AppMoreDetailTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AppMoreDetailPresenter.
 *
 * @package namespace App\App\Eloquent\Presenters;
 */
class AppMoreDetailPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AppMoreDetailTransformer();
    }
}
