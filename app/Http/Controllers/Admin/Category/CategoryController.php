<?php

namespace App\Http\Controllers\Admin\Category;

use Illuminate\Http\Request;
use Modules\Category\Eloquent\Repositories\CategoryRepositoryEloquent;
use Modules\Category\Http\Controllers\CategoryController as Controller;
use App\App\Eloquent\Repositories\AppRepositoryEloquent;

class CategoryController extends Controller
{

    public function __construct(CategoryRepositoryEloquent $categoryModel, AppRepositoryEloquent $appModel)
    {
        $this->setRoutes( [
            'edit_page'    => 'admin.category.detail',
            'sub_category' => 'admin.sub.category.index',

        ]);
        parent::__construct( $categoryModel );

        $this->appModel = $appModel;
    }

    public function getIndex()
    {
        $data = [];
        return view('admin.category.index', $data);
    }

    public function getSubCategoryIndex($id = null)
    {
        $data = [
            'parent_id' => $id
        ];
        return view('admin.category.index', $data);
    }

    public function getDetail($id)
    {
        $item = $this->detail($id);
        if( !$item ) abort(404);
        $statusCollections = $this->statusCollections();
        return view('admin.category.detail', compact('item', 'statusCollections'));
    }

    public function getCreate(Request $request)
    {

        $item = [
            'page_type' => 'create',
            'parent_id' => $request->has('parent_id') ? $request->get('parent_id') : null,
            'pageindex' => route('admin.category.create')
        ];

        $statusCollections = $this->statusCollections();
        return view('admin.category.detail', compact('item','statusCollections'));
    }

    public function statusCollections() {
        return $this->appModel->statusCollections();
    }
}
