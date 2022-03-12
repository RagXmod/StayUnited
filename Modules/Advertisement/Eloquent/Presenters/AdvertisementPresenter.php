<?php

namespace Modules\Advertisement\Eloquent\Presenters;

use Modules\Advertisement\Eloquent\Transformers\AdvertisementTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AdvertisementPresenter.
 *
 * @package namespace Modules\Advertisement\Eloquent\Presenters;
 */
class AdvertisementPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AdvertisementTransformer();
    }
}
