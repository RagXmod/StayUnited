<?php

namespace Modules\Category\Http\Controllers;

use Exception;
use DataTables;
use Illuminate\Http\Request;
use Modules\Core\Traits\ResponseTrait;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Category\Eloquent\Repositories\CategoryRepositoryEloquent;
class CategoryController extends BaseController
{

    use ResponseTrait;

    public $routes = [
        'edit_page' =>  null,
        'sub_category' =>  null,
    ];

    public $pageModel;

    public function __construct(CategoryRepositoryEloquent $pageModel)
    {
        parent::__construct();
        $this->pageModel = $pageModel;
    }

    public function setRoutes( $route) {
        $this->routes = array_merge($this->routes,$route);
        return $this;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        try {

            $parentId = $request->has('parent_id') ? $request->get('parent_id') : null;
            $model = $this->pageModel->makeModel()->query()->where('parent_id', $parentId);
            return $this->_getCategoryList( $model, true );

        } catch (Exception $e) {
            return $this->failed($e->getMessage());
        }
    }

    public function detail( $id ) {

        $model = $this->pageModel->find($id);
        return $model;
    }


    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug'  => 'required'
        ]);

        try {

            $input = $request->all();
            $input = array_filter(array_only($input,['title','description','slug','icon', 'status_identifier','seo_title','seo_description','seo_keyword','parent_id', 'identifier']));

            $input['slug'] = str_slug($input['slug']);


            $categoryModelObj = $this->pageModel->findWhere(['slug' => $input['slug']] )->first();
            if($categoryModelObj)
                throw new Exception(sprintf("Category Slug exists (%s), try different slug name ",$input['slug']));

            $user = $this->auth->user();
            if ( $user )
                $input['user_id'] = $user->id;

            if ( !isset($input['status_identifier']))
                $input['status_identifier'] = 'active';

            if(isset($input['seo_keyword']) && !empty($input['seo_keyword']))
                $input['seo_keyword'] = arrayKeywordsToCommaString( $input['seo_keyword'] );


            if ( !isset($input['identifier'])){
                $input['identifier'] = str_slug($input['slug'], '_');
            }

            $categoryModel = $this->pageModel->create($input);
            if($categoryModel) {

                $categoryModelArr = $categoryModel->toArray();
                $categoryModelArr['dcm_detail_url'] = route($this->routes['edit_page'], $categoryModel->id);

                return $this->success( $categoryModelArr );
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
                    return $this->success( sprintf('Successfully deleted category (%s).',$dataObj->title));
            }
            return $this->failed('Failed to delete category.');

        } catch (Exception $e) {
            return $this->failed($e->getMessage());
        }
    }


    private function _getCategoryList( $model, $isParent = false ) {

        return DataTables::eloquent($model)
                ->order(function ($query) {
                    $query->orderBy('created_at', 'desc');
                })
                ->editColumn('title', function ($item) {
                    return str_limit(e($item->title), 40);
                })

                ->addColumn('action', function ($item) use ( $isParent) {
                    $editLink  = route($this->routes['edit_page'], $item->id);


                    $_parentBtn = '';
                    $subCategoryLink = route($this->routes['sub_category'], $item->id);
                    if ( $isParent === true) {
                        $_parentBtn = "
                            <a href='$subCategoryLink' class='btn btn-sm btn-primary'>
                                <i class='fas fa-angle-double-right'></i> Sub-categories
                            </a>
                        ";
                    }
                    $item = "
                        {$_parentBtn}
                        <a href='$editLink' class='btn btn-sm btn-success'>
                            <i class='fas fa-pen'></i> Edit
                        </a>
                        <button class='btn btn-sm btn-dark del_btn' data-pageid='$item->id'>
                            <i class='fas fa-trash'></i> Delete
                        </button>
                    ";

                    return $item;
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


    }
}
