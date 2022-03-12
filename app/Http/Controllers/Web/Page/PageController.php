<?php

/**
 * Module Core: App\Http\Controllers\Web\Page\PageController
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

namespace App\Http\Controllers\Web\Page;


use Modules\Core\Http\Controllers\BaseController;
use Modules\Page\Eloquent\Repositories\PageRepositoryEloquent;

use SEOMeta;
use OpenGraph;
use Twitter;
class PageController extends BaseController
{

    /**
     * Undocumented function
     *
     * @param PageRepositoryEloquent $page
     */
    public function __construct(PageRepositoryEloquent $pageModel) {

        $this->pageModel = $pageModel;
    }


    public function getIndex() {

        $pages = $this->pageModel->getAllPages();
        $data =  collect($pages)
                    ->whereIn('status_identifier', config('page.status.published'))
                    ->toArray();

        return view('web.page.index', ['pages' => $pages]);
    }

    public function getPage( $slug) {

        $page = $this->pageModel->findByPageSlug($slug);

        if ( !$page )
            abort(500);

        $title = ($page['seo_title'] ?? $page['title']) . ' - '. dcmConfig('site_name');
        $desc  = str_limit(($page['seo_description'] ?? $page['content']) , 160);
        $url = $page['link'] ?? url('/') ;
        $logo     = dcmConfig('site_logo');

        SEOMeta::setTitle($title)
                    ->setDescription( e($desc) )
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

        return view('web.page.detail', $page);
    }
}
