<?php

namespace App\App\Eloquent\Presenters;

use App\App\Eloquent\Transformers\AppVersionTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AppVersionPresenter.
 *
 * @package namespace App\App\Eloquent\Presenters;
 */
class AppVersionPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AppVersionTransformer();
    }
}
