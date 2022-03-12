<?php

namespace App\App\Eloquent\Presenters;

use App\App\Eloquent\Transformers\AppDeveloperTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AppDeveloperPresenter.
 *
 * @package namespace App\App\Eloquent\Presenters;
 */
class AppDeveloperPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AppDeveloperTransformer();
    }
}
