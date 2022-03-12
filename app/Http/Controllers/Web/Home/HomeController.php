<?php

/**
 * Module Core: App\Http\Controllers\Web\Home\HomeController
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http   : //devcorpmanila.com
 */

namespace App\Http\Controllers\Web\Home;

use SEOMeta;
use Twitter;
use OpenGraph;

use Illuminate\Http\Request;
use Facades\App\App\Facades\AppFacade;
use Modules\Core\Http\Controllers\BaseController;
use App\App\Eloquent\Repositories\HomeAdsPlacementBlockRepositoryEloquent;

class HomeController extends BaseController
{
    public function getIndex()
    {
        $data = [
            'apps'        => AppFacade::activeFeaturedPosts(),
            'sliders'     => AppFacade::homeImageSliders(),
            'newest_apps' => AppFacade::newestApps(),
        ];


        $title    = dcmConfig('meta_title');
        $desc     = dcmConfig('meta_description');
        $keywords = dcmConfig('meta_keywords');
        $logo     = dcmConfig('site_logo');
        $url      = url('/');

        SEOMeta:: setTitle($title)
                ->setDescription( str_limit( trim(strip_tags($desc)), 160))
                ->setKeywords($keywords)
                ->setCanonical(  $url    );


        SEOMeta:: addMeta('article:published_time', now()->toW3CString(), 'property');
        SEOMeta:: addMeta('article:modified_time',  now()->toW3CString(), 'property');
        SEOMeta:: addMeta('article:tag', $keywords, 'property');

        OpenGraph:: setDescription($desc);
        OpenGraph:: setTitle($title);
        OpenGraph:: setUrl(  $url  );
        OpenGraph:: addProperty('type', 'article');
        OpenGraph:: addProperty('locale', app()->getLocale());
        OpenGraph:: addProperty('locale:alternate', ['en-us']);
        OpenGraph:: addImage($logo);


        // You can chain methods
        Twitter:: setType('article')
                    ->setImage( $logo )
                    ->setTitle($title)
                    ->setDescription($desc)
                    ->setUrl(  $url  )
                    ->setSite($title);


        return view('web.home.index', $data);
    }

    public function getLatest( $slug = null)
    {
        $data = AppFacade::appFeaturedPostModel()->findBySlug($slug);
        return view('web.home.latest', $data);
    }

}
