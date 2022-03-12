<?php

namespace App\Http\Controllers\Admin\Setup;

use DB;
use Illuminate\Http\Request;
use Modules\Core\Traits\ResponseTrait;
use Modules\Core\Traits\UploadTrait;
use Modules\Core\Http\Controllers\BaseController;
use App\Http\Controllers\Admin\Setup\Traits\NavigationTrait;
use App\App\Eloquent\Repositories\HomeSliderRepositoryEloquent;

class SliderController extends BaseController
{
    use NavigationTrait, ResponseTrait, UploadTrait;


    const SLIDER_UPLOAD_DISK = 'slider-uploads';
    const HOME_SLIDER_PATH   = 'sliders';

    public function __construct(HomeSliderRepositoryEloquent $homeSliderModel)
    {
        $this->homeSliderModel = $homeSliderModel;
    }

    public function getIndex()
    {
        if ( file_exists(public_path('storage')) )
            unlink(public_path('storage'));

        \Artisan::call('storage:link');

        $sliders = $this->homeSliderModel->sliders();

        $data = [

            'upload_size_limit' => fileUploadMaxSizeLimit(),
            'navigations'       => $this->getNavigations(),
            'sliders'           => $sliders,
        ];
        return view('admin.setup.slider', $data);
    }


    public function store(Request $request)
    {

        $request->validate([
            'image' => 'required'
        ]);

        try {

            DB:: beginTransaction();

                $input = $request->all();
                $input = array_filter($input);

                if($input['image'] instanceof \Illuminate\Http\UploadedFile)
                {

                    $_file = $this->uploadFileInfo( $input['image'] );

                    $origFileName = $_file['name'];
                    $extension    = $_file['extension'];
                    $size         = $_file['size'];

                    $siteName       = str_slug(dcmConfig('site_name'));
                    $folderPathName = self::HOME_SLIDER_PATH;
                    $randomFileName = now()->format('ymdHis');
                    $fileName       = "{$siteName}-slider-{$randomFileName}.{$extension}";

                    $input['size'] = $size;
                    $input['name'] = $origFileName;

                    $storedPath =  $input['image']->storeAs(
                        $folderPathName, $fileName, self::SLIDER_UPLOAD_DISK
                    );

                    $input['path'] = $storedPath;
                }


                $model = $this->homeSliderModel->makeModel()->create($input);
                // pre($model);
                // dd($input);

            DB:: commit();

            return $this->success([
                'message'        => 'Successfully uploaded',
                'title'          => $model['title'] ?? ''
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

            $modelObj = $this->homeSliderModel->find($id);
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

}
