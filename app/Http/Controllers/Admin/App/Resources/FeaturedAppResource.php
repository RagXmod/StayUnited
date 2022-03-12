<?php

namespace App\Http\Controllers\Admin\App\Resources;

use Exception;
use Illuminate\Http\Request;
use Modules\Core\Traits\ResponseTrait;
use Modules\Core\Http\Controllers\BaseController;
use App\App\Eloquent\Repositories\AppFeaturedPostRepositoryEloquent;
use DataTables;
use DB;
class FeaturedAppResource extends BaseController
{

    use ResponseTrait;

    public $appFeaturedPostModel;

    public $routes = [
        'edit_page' => null
    ];

    public function __construct( AppFeaturedPostRepositoryEloquent $appFeaturedPostModel)
    {
        parent:: __construct();
        $this->setRoutes( [
            'edit_page' => 'admin.featured.app.detail'
        ]);
        $this->appFeaturedPostModel = $appFeaturedPostModel;
    }

    public function setRoutes( $route) {
        $this->routes = array_merge($this->routes,$route);
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

            $model = $this->appFeaturedPostModel->makeModel()->query();
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
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {

            $modelObj = $this->appFeaturedPostModel->find($id);
            if($modelObj)
            {
                $dataObj   = $modelObj;
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

        $model = $this->appFeaturedPostModel->find($id);
        return $model;
    }


    public function store(Request $request) {

        $request->validate([
            'title' => 'required',
            'slug'  => 'required'
        ]);

        try {

            $input         = $request->all();
            $input['slug'] = str_slug($input['slug']);

            DB::beginTransaction();

            $appFeaterdPostModel = $this->appFeaturedPostModel->findWhere(['slug' => $input['slug']] )->first();
            if($appFeaterdPostModel)
                throw new Exception(sprintf("App featured slug exists (%s), try different slug name ",$input['slug']));

            $user = $this->auth->user();
            if ( $user )
                $input['user_id'] = $user->id;

            if ( !isset($input['status_identifier']))
                $input['status_identifier'] = 'active';

            if(isset($input['seo_keyword']) && !empty($input['seo_keyword']))
                $input['seo_keyword'] = arrayKeywordsToCommaString( $input['seo_keyword'] );

            $appFeaturedModel                 = $this->appFeaturedPostModel->create($input);
            if($appFeaturedModel) {

                if ( isset($input['apps']) ) {
                    $this->connectApps( $input['apps'], $appFeaturedModel );
                }

                DB::commit();
                $appFeaturedModelArr                   = $appFeaturedModel->toArray();
                $appFeaturedModelArr['dcm_detail_url'] = route($this->routes['edit_page'], $appFeaturedModel->id);
                return $this->success( $appFeaturedModelArr );
            }

        } catch (Exception $e) {
            DB::rollback();
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

            $input         = $request->all();
            $input['slug'] = str_slug($input['slug']);

            DB::beginTransaction();

            $appFeaterdPostModel = $this->appFeaturedPostModel->find($id);
            if(!$appFeaterdPostModel)
                throw new Exception("Failed to find app id.");

            if($appFeaterdPostModel->slug != $input['slug'])
            {
                $obj = $this->appFeaturedPostModel->makeModel()->where('slug',$input['slug'])->first();
                if($obj)
                    throw new Exception("Slug must be unique, please try again", 1);
            }

            $user = $this->auth->user();
            if ( $user )
                $input['user_id'] = $user->id;

            if ( !isset($input['status_identifier']))
                $input['status_identifier'] = 'active';

            if(isset($input['seo_keyword']) && !empty($input['seo_keyword']))
                $input['seo_keyword'] = arrayKeywordsToCommaString( $input['seo_keyword'] );



            if ( isset($input['apps']) ) {
                $this->connectApps( $input['apps'], $appFeaterdPostModel );
            }

            $appFeaterdPostModel->fill($input)->save();

            DB::commit();

            $appFeaturedModelArr                   = $appFeaterdPostModel->toArray();
            $appFeaturedModelArr['dcm_detail_url'] = route($this->routes['edit_page'], $appFeaterdPostModel->id);
            return $this->success( $appFeaturedModelArr );


        } catch (Exception $e) {
            DB::rollback();
            return $this->failed($e->getMessage());
        }
    }

    public function connectApps( $apps, $model ) {

        $arrayAppIds = array_pluck($apps, ['id']);
        $appIds      = [];
        $positionIds = [];
        foreach ($arrayAppIds as $index => $id) {
            $appIds     [] = $id;
            $positionIds[] = ['position' => ++$index];
        }
        $collectArray = array_combine($appIds, $positionIds);
        $model->apps()->sync( $collectArray );
        return true;
    }
}