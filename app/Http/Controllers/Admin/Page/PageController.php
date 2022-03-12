<?php

namespace App\Http\Controllers\Admin\Page;
use Modules\Page\Eloquent\Repositories\PageRepositoryEloquent;
use Modules\Page\Http\Controllers\PageController as Controller;


class PageController extends Controller
{

    public function __construct(PageRepositoryEloquent $pageModel)
    {
        $this->setRoutes( [
            'edit_page' => 'admin.page.detail'
        ]);
        parent::__construct( $pageModel );
    }

    public function getIndex()
    {
        $data = [];
        return view('admin.page.index', $data);
    }


    public function getDetail($id)
    {
        $item = $this->detail($id);
        if( !$item ) abort(404);
        $statusCollections = $this->statusCollections();
        return view('admin.page.detail', compact('item', 'statusCollections'));
    }

    public function getCreate()
    {
        $item = [
            'page_type' => 'create',
            'pageindex' => route('admin.page.index')
        ];
        $statusCollections = $this->statusCollections();
        return view('admin.page.detail', compact('item','statusCollections'));
    }
}
