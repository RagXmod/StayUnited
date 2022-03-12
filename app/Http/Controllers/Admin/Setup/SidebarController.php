<?php

namespace App\Http\Controllers\Admin\Setup;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BaseController;
use App\Http\Controllers\Admin\Setup\Traits\NavigationTrait;

class SidebarController extends BaseController
{
    use NavigationTrait;

    public function __construct()
    {

    }

    public function getIndex()
    {
        $data = [

            'navigations'     => $this->getNavigations()
        ];
        return view('admin.setup.sidebar', $data);
    }


    public function getDetail($id)
    {
        // $item = $this->detail($id);
        // if( !$item ) abort(404);
        // $statusCollections = $this->statusCollections();
        // return view('admin.page.detail', compact('item', 'statusCollections'));
    }

    public function getCreate()
    {
        // $item = [
        //     'page_type' => 'create',
        //     'pageindex' => route('admin.page.index')
        // ];
        // $statusCollections = $this->statusCollections();
        // return view('admin.page.detail', compact('item','statusCollections'));
    }
}
