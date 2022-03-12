<?php


/**
 * Module Core Providers: Modules\Core\Providers\DCMServiceProvider
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */


namespace Modules\Review\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Modules\Review\Eloquent\Interfaces\ReviewRepository;
use Modules\Review\Eloquent\Entities\Review;

/**
 * Class ReviewRepositoryEloquent.
 *
 * @package namespace Modules\Review\Eloquent\Repositories;
 */
class ReviewRepositoryEloquent extends BaseRepository implements ReviewRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Review::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
