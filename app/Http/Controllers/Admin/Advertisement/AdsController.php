<?php

namespace App\Http\Controllers\Admin\Advertisement;

use Illuminate\Http\Request;
use Modules\Advertisement\Eloquent\Repositories\AdvertisementRepositoryEloquent;
use Modules\Advertisement\Http\Controllers\AdvertisementController as Controller;



class AdsController extends Controller
{

    public function __construct(AdvertisementRepositoryEloquent $adsModel)
    {
        $this->setRoutes( [
            'edit_page' => 'admin.ads.detail'
        ]);
        parent::__construct( $adsModel );
    }

    public function getIndex()
    {
        $data = [];
        return view('admin.advertisement.index', $data);
    }


    public function getDetail($id)
    {
        $item = $this->detail($id);
        if( !$item ) abort(404);

        return view('admin.advertisement.detail', compact('item'));
    }

    public function getCreate()
    {
        $item = [
            'page_type' => 'create',
            'pageindex' => route('admin.page.index')
        ];
        return view('admin.advertisement.detail', compact('item'));
    }

}
