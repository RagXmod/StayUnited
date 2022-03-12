<?php

namespace Modules\Page\Http\Controllers;

use Exception;
use DataTables;
use Illuminate\Http\Request;
use Modules\Core\Traits\ResponseTrait;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Page\Eloquent\Repositories\PageRepositoryEloquent;

class PageController extends BaseController
{

    use ResponseTrait;

    public $routes = [
        'edit_page' =>  null
    ];

    public $pageModel;

    public function __construct(PageRepositoryEloquent $pageModel)
    {
        parent::__construct();
        $this->pageModel = $pageModel;
    }

    public function setRoutes( $route) {
        $this->routes  = array_merge($this->routes,$route);
        return $this;
    }

    /**
    *
    * index()
    *
    * @return JSON
    * @access  public
    **/
    public function index()
    {

        try {

            $model = $this->pageModel->makeModel()->query();
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
     * Update the given user.
     *
     * @param  Request  $request
     * @param  string  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'slug'  => 'required'
        ]);

        try {

            $model = $this->pageModel->find($id);
            $input = $request->all();

            if(!$model)
                throw new Exception("Failed to find page id.");

            $input['slug'] = str_slug($input['slug']);

            if($model->slug != $input['slug'])
            {
                $obj  = $this->pageModel->makeModel()->where('slug',$input['slug'])->first();
                if($obj)
                    throw new Exception("Slug must be unique, please try again", 1);
            }

            $input['page_id'] = (isset($input['page_id']) ) ? $input['page_id']: 0;

            if(isset($input['seo_keyword']) && !empty($input['seo_keyword']))
                $input['seo_keyword'] = arrayKeywordsToCommaString( $input['seo_keyword'] );

            $input['seo_title'] = $input['seo_title'] ?? $model->title ?? 'Seo Title Here';

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

            $modelObj = $this->pageModel->find($id);
            if($modelObj)
            {
                $dataObj = $modelObj;
                $destroyed = $modelObj->delete();
                if($destroyed)
                    return $this->success( sprintf('Successfully deleted page (%s).',$dataObj->title));
            }
            return $this->failed('Failed to delete page.');

        } catch (Exception $e) {
            return $this->failed($e->getMessage());
        }
    }


    public function detail( $id ) {

        $model = $this->pageModel->find($id);
        return $model;
    }


    public function store(Request $request) {

        $request->validate([
            'title' => 'required',
            'slug'  => 'required'
        ]);

        try {

            $input = $request->all();
            $input = array_only($input,['title','content','slug','status_identifier','seo_title','seo_description','seo_keyword']);

            $input['slug'] = str_slug($input['slug']);

            $pageModelObj = $this->pageModel->findWhere(['slug' => $input['slug']] )->first();
            if($pageModelObj)
                throw new Exception(sprintf("Page Slug exists (%s), try different slug name ",$input['slug']));

            $user = $this->auth->user();
            if ( $user )
                $input['user_id'] = $user->id;


            if ( !isset($input['status_identifier']))
                $input['status_identifier'] = config('page.status.published');

            if(isset($input['seo_keyword']) && !empty($input['seo_keyword']))
                $input['seo_keyword'] = arrayKeywordsToCommaString( $input['seo_keyword'] );

            $pageModel                 = $this->pageModel->create($input);
            $pageModel->dcm_detail_url = $this->routes['edit_page'];

            if($pageModel) {

                $pageModelArr = $pageModel->toArray();
                $pageModelArr['dcm_detail_url'] = route($this->routes['edit_page'], $pageModel->id);

                return $this->success( $pageModelArr );
            }


        } catch (Exception $e) {
            return $this->failed($e->getMessage());
        }
    }

    public function statusCollections() {
        return pageStatusArr(null, true);
    }
}
