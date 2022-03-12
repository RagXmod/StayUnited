<?php

namespace Modules\Configuration\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Traits\ResponseTrait;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Configuration\Eloquent\Repositories\ConfigurationRepositoryEloquent;
use Cache;
use Image;

class ConfigurationController extends BaseController
{


    use ResponseTrait;

    public $routes = [
        'edit_page' =>  null
    ];

    public $configurationModel;


    public function __construct(ConfigurationRepositoryEloquent $configurationModel)
    {
        parent::__construct();
        $this->configurationModel = $configurationModel;
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
        return view('configuration::index');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        // $request->validate([
        //     'titles'      => 'required',
        //     'identifier' => 'required'
        // ]);

        try {

            $input = $request->all();


            if(isset($input['meta_keywords']) && !empty($input['meta_keywords']))
                $input['meta_keywords'] = arrayKeywordsToCommaString( $input['meta_keywords'] );


            $input['group'] =  str_slug($input['title'] ?? '');


            foreach($input as $identifier => $item) {

                if ( in_array($identifier, ['group','title', 'status', 'timezonelist', 'languages', 'countries']) )
                    continue;

                if ( !$identifier )
                    continue;

                if ( is_array($identifier) )
                    continue;

                if (  $identifier === 'site_logo' ) {

                    $siteLogoPath = '/img/site-logo.png';
                    $img = Image::make(file_get_contents( $item ));

                    $img->save( public_path($siteLogoPath));
                    $item = $siteLogoPath;
                }
                $this->configurationModel
                    ->makeModel()
                    ->updateOrCreate([
                        'identifier' => $identifier,
                        'group'      => $input['group'],
                    ], [
                        'value' => ($item) ? $item : ''
                    ]);

            }

            return $this->success( $input );

        } catch (Exception $e) {
            logger()->debug($e);
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

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {

            // $modelObj = $this->configurationModel->find($id);
            // if($modelObj)
            // {
            //     $dataObj = $modelObj;
            //     $destroyed = $modelObj->delete();
            //     if($destroyed)
            //         return $this->success( sprintf('Successfully deleted ads (%s).',$dataObj->title));
            // }
            // return $this->failed('Failed to delete ads.');

        } catch (Exception $e) {
            return $this->failed($e->getMessage());
        }
    }


    public function getAllTimezones() {

        return Cache::rememberForever(config('config.config_timezone_cache_key'), function () {

            $timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
            // return \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
            $_collections = [];
            foreach($timezones as $item) {
                $_collections[] = [
                    'label' => $item,
                    'value' => $item
                ];
            }
            return $_collections;
        });

    }
}
