<?php

namespace Modules\User\Eloquent\Presenters;

use Modules\User\Eloquent\Transformers\UserDetailTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class UserDetailPresenter.
 *
 * @package namespace Modules\User\Eloquent\Presenters;
 */
class UserDetailPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new UserDetailTransformer();
    }
}
