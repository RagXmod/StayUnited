<?php

namespace Modules\User\Eloquent\Presenters;

use Modules\User\Eloquent\Transformers\UserUploadTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class UserUploadPresenter.
 *
 * @package namespace Modules\User\Eloquent\Presenters;
 */
class UserUploadPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new UserUploadTransformer();
    }
}
