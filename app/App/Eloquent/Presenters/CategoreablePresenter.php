<?php

namespace App\App\Eloquent\Presenters;

use App\App\Eloquent\Transformers\CategoreableTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class CategoreablePresenter.
 *
 * @package namespace App\App\Eloquent\Presenters;
 */
class CategoreablePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new CategoreableTransformer();
    }
}
