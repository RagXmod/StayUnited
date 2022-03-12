<?php

/**
 * Module Core: App\Http\Controllers\Web\App\DeveloperController
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

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Traits\ResponseTrait;
use App\App\Eloquent\Repositories\AppDeveloperRepositoryEloquent;

use Exception;
use SEOMeta;
use OpenGraph;
use Twitter;

class DeveloperController extends BaseController
{
    use ResponseTrait;

    public function getDeveloper( $slug )
    {

        try {

            $developerModel = app(AppDeveloperRepositoryEloquent::class);
            $developer      = $developerModel->findByDeveloperSlug($slug);



            $title = ($developer->seo_title ?? $developer->title) . ' - '. dcmConfig('site_name');
            $desc  = str_limit(($developer->seo_description ?? $developer->description) , 160);
            $url = $developer->developer_detail_url ?? url('/') ;
            $logo     = dcmConfig('site_logo');

            SEOMeta::setTitle($title)
                        ->setDescription( $desc )
                        ->setCanonical( $url );


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

            $data     = [
                'apps'           => $developer->apps,
                'developer'       => $developer
            ];
            return view('web.app.developer', $data);

        } catch (Exception $e) {
            abort(404);
        }

    }

}