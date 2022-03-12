<?php

namespace App\App\Eloquent\Repositories;

use Exception;
use App\App\Eloquent\Entities\AppDeveloper;
use Prettus\Repository\Eloquent\BaseRepository;
use Modules\Core\Traits\RepositoryEloquentTrait;

use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Interfaces\AppDeveloperRepository;
/**
 * Class AppDeveloperRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class AppDeveloperRepositoryEloquent extends BaseRepository implements AppDeveloperRepository
{

    use RepositoryEloquentTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AppDeveloper::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }



    public function findByDeveloperSlug($slug) {
        $developerModel = $this->model
                                ->with(['apps'])
                                // ->isActive()
                                ->bySlug($slug);
        $dataModel = $developerModel->first();
        if(!$dataModel)
            throw new Exception("{$slug} does not exists in our database. Please contact us to add it for you.");

        return $dataModel;
    }

    public function findByDeveloperId($identifier) {

        $developerModel = $this->model
                        ->with(['apps'])
                        // ->isActive()
                        ->byIdentifier($identifier);

        $dataModel = $developerModel->first();
        if(!$dataModel)
            throw new Exception("{$identifier} does not exists in our database. Please contact us to add it for you.");

        return $dataModel;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function appDeveloperCollections()
    {
        return $this->cachemodel();
    }

}
