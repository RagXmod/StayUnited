<?php

namespace App\Http\Controllers\Admin\App;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BaseController;
use App\App\Eloquent\Repositories\AppRepositoryEloquent;
use App\App\Eloquent\Repositories\CategoryRepositoryEloquent;
use App\App\Eloquent\Repositories\AppDeveloperRepositoryEloquent;

class AppController extends BaseController
{

    public function __construct(AppRepositoryEloquent $appModel,
                        AppDeveloperRepositoryEloquent  $appDeveloperModel,
                        CategoryRepositoryEloquent $categoryModel)
    {
       $this->appModel          = $appModel;
       $this->appDeveloperModel = $appDeveloperModel;
       $this->categoryModel     = $categoryModel;
    }

    public function getIndex()
    {
        $letters  = range('A', 'Z');
        array_unshift($letters, 'All');

        $data = [
            'letters' => $letters,
            'navigations' => $this->appModel->getNavigations()
        ];
        return view('admin.app.index', $data);
    }


    public function getDetail($id)
    {

        $item =  $this->appModel->findById($id);
        if( !$item ) abort(404);
        $data = array_merge([
            'item' => $item,
        ], $this->_requiredData() );

        return view('admin.app.detail', $data);
    }

    public function getCreate()
    {
        $data = array_merge([
            'item'                => [
                'page_type'=> 'create',
            ],
            'pageindex'           => route('admin.app.index'),
        ], $this->_requiredData() );
        return view('admin.app.detail', $data);
    }


    public function getCreateAppFromStore()
    {

        $data = array_merge([
            'pageindex'           => route('admin.app.index'),
        ], $this->_requiredData() );
        return view('admin.app.create-store', $data);
    }


    public function _requiredData() {

        $statusCollections    = $this->appModel->statusCollections();
        $categoryCollections  = $this->categoryModel->categoryOptionsForApps();
        $navigations          = $this->appModel->getNavigations();
        $developerCollections = $this->appDeveloperModel->appDeveloperCollections();

        return [

            'statusCollections'    => $statusCollections,
            'categoryCollections'  => $categoryCollections,
            'developerCollections' => $developerCollections,
            'navigations'          => $navigations,
            'upload_size_limit'    => fileUploadMaxSizeLimit(),
        ];
    }

}
