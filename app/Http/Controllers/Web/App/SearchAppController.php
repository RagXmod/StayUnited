<?php

/**
 * Module Core: App\Http\Controllers\Web\App\SearchAppController
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */


namespace App\Http\Controllers\Web\App;

use Exception;
use Illuminate\Http\Request;
use Modules\Core\Traits\ResponseTrait;

use Modules\Core\Http\Controllers\BaseController;
use App\App\Eloquent\Repositories\AppRepositoryEloquent;

use SEOMeta;
use OpenGraph;
use Twitter;

class SearchAppController extends BaseController
{
    use ResponseTrait;

    public function getSearch( Request $request )
    {

        try {

            $appModel = app(AppRepositoryEloquent::class);

            $input = $request->all();
            $searchInput = $request->has('q') ? $request->get('q') : '';

            $searchResults = [];
            if ( $searchInput )
                $searchResults = $appModel->search($searchInput);



            $title = trans('dcm.search_seo_title', ['attr' => $searchInput]) . ' - '. dcmConfig('site_name');
            $desc  = str_limit($title, 160);
            $url =  route('web.app.search',['q' => $searchInput]);
            $logo     = dcmConfig('site_logo');

            SEOMeta::setTitle($title)
                        ->setDescription( $desc )
                        ->setCanonical(  route('web.app.search',['q' => $searchInput]) );


            SEOMeta::addKeyword( explode('-', str_slug($title) ));


            SEOMeta::addMeta('article:published_time', now()->toW3CString(), 'property');
            SEOMeta::addMeta('article:modified_time', now()->toW3CString(), 'property');
            SEOMeta::addMeta('article:section', $title, 'property');
            SEOMeta::addMeta('article:tag', $title, 'property');

            OpenGraph::setDescription($desc);
            OpenGraph::setTitle($title);
            OpenGraph::setUrl( $url );
            OpenGraph::addProperty('type', 'article');
            OpenGraph::addProperty('locale', app()->getLocale());
            OpenGraph::addProperty('locale:alternate', ['en-us']);
            OpenGraph::addImage($logo );


            // // You can chain methods
            Twitter::setType('article')
                        ->setImage($logo )
                        ->setTitle($title)
                        ->setDescription($desc)
                        ->setUrl( $url )
                        ->setSite($title);

            return view('web.app.search')->with(compact('searchResults','searchInput'));

        } catch (Exception $e) {
            abort(404);
        }

    }

}