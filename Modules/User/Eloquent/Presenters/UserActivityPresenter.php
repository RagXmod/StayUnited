<?php

namespace Modules\User\Eloquent\Presenters;

use Modules\User\Eloquent\Transformers\UserActivityTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class UserActivityPresenter.
 *
 * @package namespace Modules\User\Eloquent\Presenters;
 */
class UserActivityPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new UserActivityTransformer();
    }
}
