<?php

namespace App\Http\Controllers\Admin\Setup;

use Illuminate\Http\Request;
use Modules\Core\Traits\ResponseTrait;
use Modules\Core\Http\Controllers\BaseController;
use App\Http\Controllers\Admin\Setup\Traits\NavigationTrait;
use App\App\Eloquent\Repositories\AdvertisementRepositoryEloquent;
use App\App\Eloquent\Repositories\HomeAdsPlacementBlockRepositoryEloquent;
use Exception;

class AdsPlacementController extends BaseController
{
    use NavigationTrait, ResponseTrait;

    public function __construct(AdvertisementRepositoryEloquent $adsModel,
                HomeAdsPlacementBlockRepositoryEloquent $homeAdsPlacementModel)
    {
        $this->adsModel              = $adsModel;
        $this->homeAdsPlacementModel = $homeAdsPlacementModel;
    }

    public function getIndex()
    {
        $data = [

            'navigations'               => $this->getNavigations(),
            'ads_collections'           => $this->adsModel->adsCollections(),
            'ads_placement_collections' => $this->homeAdsPlacementModel->adsPlacementWithChildrenCollections()->toArray(),
        ];
        return view('admin.setup.ads-placement', $data);
    }


    public function store(Request $request)
    {

        $input = $request->all();
        $input = array_first($input);

        $adsPlacementCollections = $this->homeAdsPlacementModel->get();
        foreach($input as $item) {

            $parentPlacementBlockModel = $adsPlacementCollections->where('id',$item['id'])->first();
            if ( $parentPlacementBlockModel) {
                $childrenArr = array_first($item['children'] ?? []);

                $childrenIds = array_pluck($childrenArr, 'id');
                $parentPlacementBlockModel->ads()->sync($childrenIds);
            }
        }
        cache()->forget('ads_collections');
        return $this->success('Successfully created / updated.');


    }
}
