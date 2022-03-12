<?php

/**
 * Module Core: App\Http\Controllers\Web\App\CategoryController
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
use Modules\Category\Eloquent\Repositories\CategoryRepositoryEloquent;

use SEOMeta;
use OpenGraph;
use Twitter;

class CategoryController extends BaseController
{
    use ResponseTrait;

    public function getDetail( $slug )
    {

        try {

            $categoryModel = app(CategoryRepositoryEloquent::class);
            $category = $categoryModel->findBySlug($slug);
            
            if (!$category)
                throw new Exception("Category not found {$slug}");

            $data     = [
                'category'    => $category,
                'categories'  => $categoryModel->getAllParentCategories(50),
                'apps'        => $category->apps()->paginate(50),
                'has_sidebar' => false
            ];


            $title = ($category->seo_title ?? $category->title) . ' - '. dcmConfig('site_name');
            $desc  = str_limit(($category->seo_description ?? $category->description) , 160);
            $url = $category->page_url;
            $logo     = dcmConfig('site_logo');

            SEOMeta::setTitle($title)
                        ->setDescription( $desc )
                        ->setCanonical( $category->page_url );


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
            // dd($data);
            return view('web.app.category', $data);

        } catch (Exception $e) {
            logger()->debug($e);
            abort(404);
        }

    }

}