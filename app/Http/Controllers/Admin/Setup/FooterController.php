<?php

namespace App\Http\Controllers\Admin\Setup;

use Illuminate\Http\Request;
use Modules\Core\Traits\ResponseTrait;
use Modules\Core\Http\Controllers\BaseController;
use App\Http\Controllers\Admin\Setup\Traits\NavigationTrait;
use App\App\Eloquent\Repositories\CategoryRepositoryEloquent;
use Modules\Page\Eloquent\Repositories\PageRepositoryEloquent;
use App\App\Eloquent\Repositories\HomePageFooterRepositoryEloquent;

class FooterController extends BaseController
{
    use NavigationTrait, ResponseTrait;

    public function __construct(
        HomePageFooterRepositoryEloquent $homePageFooter,
        PageRepositoryEloquent $pageModel,
        CategoryRepositoryEloquent $categoryModel
)
{
    $this->homePageFooter  = $homePageFooter;
    $this->pageModel     = $pageModel;
    $this->categoryModel = $categoryModel;
}

    public function getIndex()
    {
        $data = [
            'navigations' => $this->getNavigations(),
            'menus'       => $this->homePageFooter->menus(),
            'pages'       => $this->pageModel->getAllPages(),
            'categories'  => $this->categoryModel->categoryCollections()->where('status_identifier','active'),
        ];
        return view('admin.setup.footer', $data);
    }


    public function store(Request $request)
    {
        try {

            $input = $request->all();
            $this->_recursiveCategories($input);

            $this->homePageFooter->makeModel()->rebuildTree($input, true);

            return $this->success('Successfully created / updated.');

        } catch (Exception $e) {
            return $this->failed($e->getMessage());
        }

        $node = $this->homePageFooter->makeModel()->create([


        ]);

    }

    public function _recursiveCategories( &$items )
    {
        foreach($items as $index => &$item) {

            if ( isset($item['children']))
               $this->_recursiveCategories($item['children']);
            else {
                $item = $item;
            }
            $item['name'] = $item['text'];

            if ( !isset($item['_lft'] ) )
                $item['_lft'] = 0;

            if ( !isset($item['_rgt'] ) )
                $item['_rgt'] = 0;

            if ( !isset($item['parent_id'] ) )
                $item['parent_id'] = null;

        }
        return $items;
    }
}
