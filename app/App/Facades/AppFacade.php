<?php

namespace App\App\Facades;


/**
 * Module Api: App\App\Facades\AppFacade
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

use Cache;
use Exception;
use CyrildeWit\EloquentViewable\Support\Period;
use App\App\Eloquent\Repositories\AppRepositoryEloquent;
use App\App\Eloquent\Repositories\HomeSliderRepositoryEloquent;
use App\App\Eloquent\Repositories\AppFeaturedPostRepositoryEloquent;
use Modules\Category\Eloquent\Repositories\CategoryRepositoryEloquent;

class AppFacade
{

    public function __construct(
        AppFeaturedPostRepositoryEloquent $appFeaturedPostModel,
        AppRepositoryEloquent $appModel,
        HomeSliderRepositoryEloquent $homeSliderModel,
        CategoryRepositoryEloquent $categoryModel
    )
    {
        $this->appFeaturedPostModel = $appFeaturedPostModel;
        $this->homeSliderModel      = $homeSliderModel;
        $this->categoryModel        = $categoryModel;
        $this->appModel             = $appModel;
    }


    public function activeFeaturedPosts() {

        return $this->appFeaturedPostModel->getAllActiveFeaturedPosts();
    }


    public function newestApps( $limit = 12) {

        return $this->appModel->newestApps( $limit );
    }

    public function homeImageSliders() {

        return $this->homeSliderModel->sliders();
    }

    public function getSideBarApps() {

        $data = [
            'most_viewed_apps' => $this->mostViewedApps(),
            'categories'       => $this->categories(),
        ];
        return $data;
    }

    public function mostViewedApps( $limit = 10) {

        $appModel = app(\App\App\Eloquent\Entities\App::class);
        return $appModel->orderByViews('desc', Period::pastMonths(1))->get()->where('views_count','>',0)->take($limit);
    }


    public function categories($limit = 15) {

        return $this->categoryModel->getAllParentCategories( $limit );
    }


    public function appFeaturedPostModel() {
        return $this->appFeaturedPostModel;
    }



}