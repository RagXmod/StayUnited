<?php

namespace Modules\Advertisement\Http\Controllers;

use Exception;
use DataTables;
use Illuminate\Http\Request;
use Modules\Core\Traits\ResponseTrait;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Advertisement\Eloquent\Repositories\AdvertisementRepositoryEloquent;

class AdvertisementController extends BaseController
{

    use ResponseTrait;

    public $routes = [
        'edit_page' =>  null
    ];

    public $adsModel;


    public function __construct(AdvertisementRepositoryEloquent $adsModel)
    {
        parent::__construct();
        $this->adsModel = $adsModel;
    }

    public function setRoutes( $route) {
        $this->routes  = array_merge($this->routes,$route);
        return $this;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try {

            $model = $this->adsModel->makeModel()->query();
            return DataTables::eloquent($model)
                            ->order(function ($query) {
                                $query->orderBy('created_at', 'desc');
                            })
                            ->editColumn('title', function ($item) {
                                return str_limit(e($item->title), 40);
                            })

                            ->addColumn('action', function ($item) {
                                $editLink  = route($this->routes['edit_page'], $item->id);
                                return "
                                    <a href='$editLink' class='btn btn-sm btn-success'>
                                        <i class='fas fa-pen'></i> Edit
                                    </a>
                                    <button class='btn btn-sm btn-dark del_btn' data-pageid='$item->id'>
                                        <i class='fas fa-trash'></i> Delete
                                    </button>
                                ";
                            })
                            ->setRowId(function ($item) {
                                return $item->id;
                            })
                            ->setRowAttr([
                                'title' => function($item) {
                                    return str_limit(e($item->title), 30);
                                },
                            ])
                            ->escapeColumns(['title'])
                            ->toJson();

        } catch (Exception $e) {
            return $this->failed($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('advertisement::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'title'      => 'required',
            'identifier' => 'required'
        ]);

        try {

            $input = $request->all();
            $input = array_only($input,['title','ads_code','identifier']);

            $input['identifier'] = str_slug($input['identifier']);

            $adsModelObj = $this->adsModel->findWhere(['identifier' => $input['identifier']] )->first();
            if($adsModelObj)
                throw new Exception(sprintf("Ads identifier is exists (%s), try different identifier name ",$input['identifier']));

            $adsModel                 = $this->adsModel->create($input);
            $adsModel->dcm_detail_url = $this->routes['edit_page'];

            if($adsModel) {

                $adsModelArr = $adsModel->toArray();
                $adsModelArr['dcm_detail_url'] = route($this->routes['edit_page'], $adsModel->id);

                return $this->success( $adsModelArr );
            }

        } catch (Exception $e) {
            return $this->failed($e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'      => 'required',
            'identifier' => 'required'
        ]);

        try {

            $model = $this->adsModel->find($id);
            $input = $request->all();

            if(!$model)
                throw new Exception("Failed to find ads id.");

            $input['identifier'] = str_slug($input['identifier']);

            if($model->identifier != $input['identifier'])
            {
                $obj  = $this->adsModel->makeModel()->where('identifier',$input['identifier'])->first();
                if($obj)
                    throw new Exception("Identifier must be unique, please try again", 1);
            }

            $input['page_id'] = (isset($input['page_id']) ) ? $input['page_id']: 0;

            $model->fill($input);
            $model->save();

            return $this->success($input);

        } catch (Exception $e) {
            return $this->failed($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {

            $modelObj = $this->adsModel->find($id);
            if($modelObj)
            {
                $dataObj = $modelObj;
                $destroyed = $modelObj->delete();
                if($destroyed)
                    return $this->success( sprintf('Successfully deleted ads (%s).',$dataObj->title));
            }
            return $this->failed('Failed to delete ads.');

        } catch (Exception $e) {
            return $this->failed($e->getMessage());
        }
    }

    public function detail( $id ) {

        $model = $this->adsModel->find($id);
        return $model;
    }
}
