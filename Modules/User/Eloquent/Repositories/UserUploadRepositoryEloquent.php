<?php

namespace Modules\User\Eloquent\Repositories;

use Modules\User\Eloquent\Entities\UserUpload;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Modules\User\Eloquent\Observers\UserUploadObserver;
use Modules\User\Eloquent\Validators\UserUploadValidator;
use Modules\User\Eloquent\Interfaces\UserUploadRepository;

/**
 * Class UserUploadRepositoryEloquent.
 *
 * @package namespace Modules\User\Eloquent\Repositories;
 */
class UserUploadRepositoryEloquent extends BaseRepository implements UserUploadRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserUpload::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));

        UserUpload::observe(new UserUploadObserver());
    }

}
