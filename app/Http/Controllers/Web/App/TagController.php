<?php

/**
 * Module Core: App\Http\Controllers\Web\App\TagController
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
use App\App\Eloquent\Repositories\AppRepositoryEloquent;

use Exception;
use SEOMeta;
use OpenGraph;
use Twitter;

class TagController extends BaseController
{
    use ResponseTrait;

    public function __construct(AppRepositoryEloquent $appModel)
    {
        parent::__construct();
        $this->appModel = $appModel;
    }

    public function getDetail( $slug )
    {

        try {

            $slug = strip_tags(trim($slug));
            $apps = $this->appModel->makeModel()->withAnyTags($slug, 'apps')->get();


            $title = (ucfirst($slug) ?? 'Tags') . ' - '. dcmConfig('site_name');
            $desc  = str_limit(($title) , 160);
            $url = route('web.app.tag.detail', $slug) ?? url('/') ;
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

            $data = [
                'slug'    => strtoupper($slug),
                'apps'    => $apps,
                'has_sidebar' => false
            ];

            return view('web.app.tags', $data);

        } catch (Exception $e) {
            logger()->debug($e);
            abort(404);
        }
    }

}