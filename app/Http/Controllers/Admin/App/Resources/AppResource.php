<?php

namespace App\Http\Controllers\Admin\App\Resources;

use Exception;
use Illuminate\Http\Request;
use Modules\Core\Traits\ResponseTrait;
use Modules\Core\Http\Controllers\BaseController;
use App\App\Eloquent\Repositories\AppRepositoryEloquent;
use Storage;
use DB;
use Modules\Core\Traits\UploadTrait;

class AppResource extends BaseController
{

    use ResponseTrait, UploadTrait;

    const FOLDER_VERSIONS    = 'versions';
    const FOLDER_SCREENSHOTS = 'screenshots';
    const FOLDER_APPIMAGE    = 'appimage';

    const UPLOAD_TYPE_FILE     = 'screenshots';
    const UPLOAD_TYPE_LINK     = 'link';
    const UPLOAD_TYPE_APPIMAGE = 'app_image';

    public $appModel;

    public $routes = [
        'edit_page' => null
    ];

    public function __construct( AppRepositoryEloquent $appModel)
    {
        parent:: __construct();
        $this->setRoutes( [
            'edit_page' => 'admin.app.detail'
        ]);
        $this->appModel = $appModel;
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
    public function index(Request $request)
    {
       try {

            $response = $this->appModel->appCollections( $request );
            return $this->success($response);

       } catch (Exception $e) {
            logger()->debug($e);
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

            $model = $this->appModel->find($id);
            $input = array_filter($request->all());

            if(!$model)
                throw new Exception("Failed to find app id.");


            $input['slug'] = str_slug($input['slug']);

            if($model->slug != $input['slug'])
            {
                $obj = $this->appModel->makeModel()->where('slug',$input['slug'])->first();
                if($obj)
                    throw new Exception("Slug must be unique, please try again", 1);
            }

            if(isset($input['seo_keyword']) && !empty($input['seo_keyword']))
                $input['seo_keyword'] = arrayKeywordsToCommaString( $input['seo_keyword'] );

            $input['seo_title'] = $input['seo_title'] ?? $model->title ?? '';
            // $input['seo_description'] = str_limit(trim(htmlentities( ($input['   seo_description'] || '') )),150);

            if(isset($input['categories']) && !empty($input['categories'])) {
                $this->_categories($input['categories'], $model);
            }

            if(isset($input['more_details']) && !empty($input['more_details'])) {
                // process details here..
                $this->_moreDetails( $input['more_details'], $model );
            }

            if(isset($input['screenshots']) ) {
                // process details here..
                $this->_screenshots( $input['screenshots'] , $model );
            }

            if(isset($input['tags']) ) {
                // process details here..
                $this->_tags( $input['tags'] , $model );
            }

            if(isset($input['app_image_url']) && !empty($input['app_image_url'])) {
                // process details here..
                $hasImageAppUpload = $this->_appImage( $request->file('app_image_url') , $model );
                if ( $hasImageAppUpload === true ) {
                    $input['app_image_url'] = '';
                }
            }

            $model->fill($input)->save();
            return $this->success([
                            'message'        => 'Successfully Updated',
                            'title'          => $model->title,
                            'dcm_detail_url' => '#']
                        );

        } catch (Exception $e) {
            logger()->debug($e);
            return $this->failed($e->getMessage());
        }
    }

    public function store(Request $request) {

        $request->validate([
            'app_id'     => 'required',
            'title'      => 'required',
            'slug'       => 'required',
            'categories' => 'required'
        ]);

        try {

            DB:: beginTransaction();
            $input = $request->all();
            $input = array_filter($input);

            $input['slug'] = str_slug($input['slug'] ?? $input['title']);

            $appModelObj = $this->appModel->findWhere(['slug' => $input['slug'], 'app_id' =>$input['app_id']] )->first();
            if($appModelObj)
                throw new Exception(sprintf("Page Slug / App Id exists (%s), try different slug name or make unique app id",$input['slug']));

            if ( !isset($input['user_id'] )) {
                $user = $this->auth->user();
                if ( $user )
                    $input['user_id'] = $user->id;
            }

            if ( !isset($input['status_identifier']))
                $input['status_identifier'] = 'active';

            if(isset($input['seo_keyword']) && !empty($input['seo_keyword']))
                $input['seo_keyword'] = arrayKeywordsToCommaString( $input['seo_keyword'] );

            $appModel = $this->appModel->create($input);


            if($appModel) {

                $input['seo_title'] = $input['seo_title'] ?? $appModel->title ?? '';

                if(isset($input['categories']) && !empty($input['categories'])) {
                    $this->_categories($input['categories'], $appModel);
                }

                if(isset($input['more_details']) && !empty($input['more_details'])) {
                    // process details here..
                    $this->_moreDetails( $input['more_details'], $appModel );
                }

                if(isset($input['screenshots']) ) {
                    // process details here..
                    $this->_screenshots( $input['screenshots'] , $appModel );
                }

                if(isset($input['app_image_url']) && !empty($input['app_image_url'])) {
                    // process details here..
                    $hasImageAppUpload = $this->_appImage( $request->file('app_image_url') , $appModel );
                    if ( $hasImageAppUpload === true ) {
                        $input['app_image_url'] = '';
                    }
                }

                $appModelArr      = $appModel->toArray();
                $appModelArr['dcm_detail_url'] = route($this->routes['edit_page'], $appModel->id);

            }

            DB:: commit();

            return $this->success([
                'message'        => 'Successfully Created',
                'title'          => $appModelArr['title'],
                'dcm_detail_url' => route($this->routes['edit_page'], $appModel->id)
                ]
            );



        } catch (Exception $e) {
            logger()->debug($e);

            DB:: rollback();
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

            $modelObj = $this->appModel->find($id);
            if($modelObj)
            {
                $dataObj   = $modelObj;
                $destroyed = $modelObj->delete();
                if($destroyed)
                    return $this->success( sprintf('Successfully deleted app (%s).',$dataObj->title));
            }
            return $this->failed('Failed to delete app.');

        } catch (Exception $e) {
            return $this->failed($e->getMessage());
        }
    }


     /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function uploadApk(Request $request)
    {

        $request->validate([
            'app_id'      => 'required',
            'apk_version' => 'required'
        ]);

        $folderPathName = '';
        try {

            $input = array_filter($request->all());
            $model = $this->appModel->find($input['app_id']);
            if(!$model)
                throw new Exception("Failed to load app app_id.");


            $apkVersion = trim(strip_tags(str_replace(' ', '', $input['apk_version'])));

            $data = [
                'user_id'       => $input['user_id'] ?? 0,
                'identifier'    => $input['apk_version'],
                'description'   => $input['description'] ?? '',
            ];

            if( isset($input['apk_file']) ) {

                $file = $this->uploadFileInfo($input['apk_file']);

                $origFileName = $file['name'];
                $extension    = $file['extension'];
                $size         = $file['size'];

                $folderPathName = str_slug($model->app_id) . DIRECTORY_SEPARATOR.self::FOLDER_VERSIONS."/{$apkVersion}";
                $fileName       = str_slug($model->title) .  "-{$apkVersion}.{$extension}";

                $storedPath = $request->file('apk_file')->storeAs(
                    $folderPathName, $fileName, 'apk-uploads'
                );

                $data = array_merge($data, [
                    'file_path'     => $storedPath,
                    'size'          => $size,
                    'original_name' => $origFileName,
                ]);
            }

            if ( isset($input['external_link'] ) ) {
                $links              = preg_split('/\r\n|\r|\n|,/', $input['external_link']);
                $data['download_link']    = implode(',', $links);
                $data['is_link_external'] = 1;
            }

            $appVersionModel = $model->versions()
                ->updateOrCreate([
                        'identifier' => $data['identifier']
                    ], $data);

            return $this->success("Succesfully create / update version {$appVersionModel->identifier} for {$model->title}.");


        } catch (Exception $e) {

            logger()->debug($e);
            $storage = Storage:: disk('apk-uploads');
            if (  $storage->exists($folderPathName) )
                $storage->deleteDirectory($folderPathName);

            return $this->failed($e->getMessage());
        }
    }


     /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function deleteApk($id)
    {

        try {

            $appVersionModel = app(\App\App\Eloquent\Repositories\AppVersionRepositoryEloquent::class);
            $modelObj        = $appVersionModel->find($id);
            if($modelObj)
            {
                $dataObj   = $modelObj;
                $destroyed = $modelObj->delete();
                if($destroyed)
                    return $this->success( sprintf('Successfully deleted apk (%s).',$dataObj->identifier));
            }
            return $this->failed('Failed to delete apk.');

        } catch (Exception $e) {
            return $this->failed($e->getMessage());
        }
    }


    /**
     * Process categories
    */
    private function _categories($categories,$model)
    {

        $ids = array_pluck($categories,'id');
        $ids = array_filter($ids);
        if(count($ids) > 0)
            $model->categories()->sync($ids);

        return $model;
    }


    private function _tags($tags, $model) {
        $model->syncTagsWithType($tags, 'apps');
    }

    private function _moreDetails($details, $model) {

        // delete all first..
        $model->moreDetails()->each(function( $model ){
            $model->delete();
        });

        foreach($details as $index => $item) {

            $_item = array_only($item, ['identifier', 'title', 'value', 'position']);

            if ( !isset($_item['title']) || !isset($_item['value'] ))
                continue;

            if ( $_item['title'] == '') {
                $_item['title'] = "No title {$index}";
            }

            if ( !isset($_item['identifier']))
                $_item['identifier'] = str_slug($_item['title'], '_');

            $model->moreDetails()->updateOrCreate([
                'identifier' => $_item['identifier']
            ],$_item);
        }

    }

    private function _screenshots($details, $model) {


        $ids = array_filter(array_pluck($details, ['id']));

        $screentShotsEloquent = $model->screenshots();
        if ( !$ids) {
            $screentShotsEloquent->each(function($m){
                $m->delete();
            });
        }

        $screentShotsEloquent->whereNotIn('id', $ids )->each(function($m){
            $m->delete();
        });

        if( isset($details['for_uploads'])) {

            foreach($details['for_uploads'] as $index => $file) {

                if($file instanceof \Illuminate\Http\UploadedFile)
                {
                    $_file = $this->uploadFileInfo($file);

                    $origFileName = $_file['name'];
                    $extension    = $_file['extension'];
                    $size         = $_file['size'];

                    $folderPathName = str_slug($model->app_id).DIRECTORY_SEPARATOR.self::FOLDER_SCREENSHOTS;
                    $randomFileName = str_random(5) . '-'. $index;
                    $fileName       = str_slug($model->title) .  "-screenshot-{$randomFileName}.{$extension}";

                    $storedPath = $file->storeAs(
                        $folderPathName, $fileName, 'apk-uploads'
                    );

                    $data = [
                        'user_id'       => $model->user_id,
                        'file_path'     => $storedPath,
                        'original_name' => $origFileName,
                        'size'          => $size,
                        'upload_type'   => self::UPLOAD_TYPE_FILE,
                        'position'      => ++$index
                    ];
                    $model->screenshots()->create($data);
                }
            }
        } else {


            if ( $details ) {
                // most likely its from external url.
                foreach($details as $index => $image) {

                    $imageLink = $image['image_link'] ?? '';

                    if ( str_contains($imageLink, ['javascript']) )
                        continue;

                    $data = [
                        'user_id'       => $model->user_id,
                        'image_url'     => $imageLink,
                        'original_name' => str_slug(dcmConfig('site_name')).'-'. str_slug($model->title).'-'.$index,
                        'size'          => 0,
                        'upload_type'   => self::UPLOAD_TYPE_LINK,
                        'position'      => ++$index
                    ];
                    $model->screenshots()->create($data);
                }
            }


        }
    }


    private function _appImage( $file, $model) {

        $hasAppImageUpload = false;
        if($file instanceof \Illuminate\Http\UploadedFile) {

            // delete old record..
            $model->appImage()->delete();

            $_file = $this->uploadFileInfo($file);

            $origFileName = $_file['name'];
            $extension    = $_file['extension'];
            $size         = $_file['size'];

            $folderPathName = str_slug($model->app_id).DIRECTORY_SEPARATOR.self::FOLDER_APPIMAGE;
            $randomFileName = str_random(5);
            $fileName       = str_slug($model->title) .  "-app-image-{$randomFileName}.{$extension}";

            $storedPath = $file->storeAs(
                $folderPathName, $fileName, 'apk-uploads'
            );

            $data = [
                'user_id'       => $model->user_id,
                'file_path'     => @$storedPath,
                'original_name' => $origFileName,
                'size'          => $size,
                'upload_type'   => self::UPLOAD_TYPE_APPIMAGE
            ];

            $model->appImage()->create($data);
            $hasAppImageUpload = true;
        }

        return $hasAppImageUpload;
    }

}