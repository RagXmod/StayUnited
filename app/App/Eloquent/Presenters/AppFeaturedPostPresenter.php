<?php

namespace App\App\Eloquent\Presenters;

use App\App\Eloquent\Transformers\AppFeaturedPostTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AppFeaturedPostPresenter.
 *
 * @package namespace App\App\Eloquent\Presenters;
 */
class AppFeaturedPostPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AppFeaturedPostTransformer();
    }
}
