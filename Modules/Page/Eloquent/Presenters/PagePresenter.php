<?php

namespace Modules\Page\Eloquent\Presenters;

use Modules\Page\Eloquent\Transformers\PageTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PagePresenter.
 *
 * @package namespace Modules\Page\Eloquent\Presenters;
 */
class PagePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PageTransformer();
    }
}
