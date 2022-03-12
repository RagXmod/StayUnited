<?php

namespace App\Http\Controllers\Admin\App;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BaseController;
use App\App\Eloquent\Repositories\AppRepositoryEloquent;
use App\App\Eloquent\Repositories\AppFeaturedPostRepositoryEloquent;


class FeaturedAppController extends BaseController
{

    public function __construct(AppRepositoryEloquent $appModel,
            AppFeaturedPostRepositoryEloquent $appFeaturedModel)
    {
       $this->appModel         = $appModel;
       $this->appFeaturedModel = $appFeaturedModel;
    }

    public function getIndex()
    {
        $data = [
            'navigations' => $this->appModel->getNavigations()
        ];
        return view('admin.app.featured-index', $data);
    }

    public function getDetail($id)
    {

        $item = $this->appFeaturedModel->findById($id);

        if( !$item ) abort(404);
        $data = [
            'item'              => $item,
            'navigations'       => $this->appModel->getNavigations(),
            'statusCollections' => $this->appModel->statusCollections()
        ];

        return view('admin.app.featured-detail',$data);
    }

    public function getCreate()
    {
        $letters  = range('A', 'Z');
        array_unshift($letters, 'All');

        $item = [
            'page_type'   => 'create',
            'pageindex'   => route('admin.featured.apps'),
            'letters'     => $letters,
            'navigations' => $this->appModel->getNavigations()
        ];

        $statusCollections = $this->appModel->statusCollections();
        return view('admin.app.featured-detail', compact('item','statusCollections','letters'));
    }
}
