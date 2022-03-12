<?php

/**
 * Module Core: App\Http\Controllers\Web\App\ApkDetailController
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */


namespace App\Http\Controllers\Web\App;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Traits\ResponseTrait;
use App\App\Eloquent\Repositories\AppRepositoryEloquent;
use App\App\Eloquent\Repositories\AppVersionRepositoryEloquent;

use Exception;
use SEOMeta;
use OpenGraph;
use Twitter;
use Storage;

class ApkDetailController extends BaseController
{
    use ResponseTrait;

    CONST DEFAULT_APP_LIMIT_DISPLAY   = 6;
    CONST DEFAULT_APP_SIMILAR_DISPLAY = 25;
    CONST DEFAULT_PAGINATION          = 25;

    public function getDetail( $slug )
    {

        $appModel     = app(AppRepositoryEloquent::class);
        $cacheKey     = $appModel->makeModel()->cacheKeyArray('app_identifier_key');
        $slug         = str_slug(trim($slug));
        $cacheKeyName = "{$cacheKey}$slug";

        try {

            $that = $this;
            $data = cache()->remember($cacheKeyName, 1440, function ()  use($that, $appModel, $slug) {

                $app   = $appModel->findByAppSlug( $slug);
                $_data = $that->_commonDataForApp( $app );

                $similarToAppBasedOnCategory = collect();
                if ( !$app->categories->isEmpty()) {
                    $categories = $app->categories->modelKeys();

                    $similarToAppBasedOnCategory = $appModel->makeModel()->whereHas('categories', function ($q) use ($categories) {
                        $q->whereIn('categories.id', $categories);
                    })->where('id', '<>', $app->id)->get()->take(self::DEFAULT_APP_LIMIT_DISPLAY)->unique();
                }

                $moreAppsFromDeveloper = collect();
                if ( !$app->developer->isEmpty()) {

                    $getFirstDeveloper = $app->developer->first();
                    if ( $getFirstDeveloper ) {
                       $getFirstDeveloper->load('apps');

                       $myapps                = $getFirstDeveloper->apps;
                       $moreAppsFromDeveloper = $myapps->where('id', '<>', $app->id)->take(self::DEFAULT_APP_LIMIT_DISPLAY)->unique();
                    }
                }
                return array_merge([
                    'app'            => $app,
                    'similar_apps'   => $similarToAppBasedOnCategory,
                    'developer_apps' => $moreAppsFromDeveloper,
                    'developer'      => $getFirstDeveloper ?? '',
                ], $_data);
            });

            $_app = $data['app'];

            $title = $_app->seo_title . ' - '. dcmConfig('site_name');
            $desc  = str_limit( trim(strip_tags($_app->description)), 160);

            SEOMeta::setTitle($title)
                        ->setDescription( $desc )
                        ->setCanonical( $_app->app_detail_url );

            if ( $_app->seo_keywords != '')
                SEOMeta:: setKeywords($_app->seo_keywords);
            else
                SEOMeta::addKeyword( explode('-', str_slug($title) ));


            SEOMeta::addMeta('article:published_time', $_app->created_at->toW3CString(), 'property');
            SEOMeta::addMeta('article:modified_time', $_app->updated_at->toW3CString(), 'property');
            SEOMeta::addMeta('article:section', array_first($_app->categories->pluck('title')), 'property');
            SEOMeta::addMeta('article:tag', $_app->categories->pluck('title'), 'property');

            OpenGraph::setDescription($desc);
            OpenGraph::setTitle($title);
            OpenGraph::setUrl( $_app->app_detail_url );
            OpenGraph::addProperty('type', 'article');
            OpenGraph::addProperty('locale', app()->getLocale());
            OpenGraph::addProperty('locale:alternate', ['en-us']);
            OpenGraph::addImage($_app->app_image_url);


            // You can chain methods
            Twitter::setType('article')
                        ->setImage( $_app->app_image_url )
                        ->setTitle($title)
                        ->setDescription($desc)
                        ->setUrl( $_app->app_detail_url )
                        ->setSite($title);

            // track views every 5mins.
            views($_app)->delayInSession(5)->record();

            return view('web.app.detail', $data);

        } catch (Exception $e) {
            logger()->debug($e);

            cache()->forget($cacheKeyName);
            abort(404);

        }

    }

    public function getAppVersions( $slug   ) {

        try {

            $appModel = app(AppRepositoryEloquent::class);
            $app      = $appModel->findByAppSlug( $slug);

            $_data = $this->_commonDataForApp( $app );
            $data     = array_merge([
                'app' => $app,
            ], $_data);


            SEOMeta::setTitle($app->seo_title . ' - '. dcmConfig('site_name'))
                    ->setDescription( str_limit( trim(strip_tags($app->description)), 160))
                    ->setKeywords($app->seo_keywords)
                    ->setCanonical( $app->app_detail_url );


            $title = $app->seo_title . ' - '. dcmConfig('site_name');
            $desc  = str_limit( trim(strip_tags($app->description)), 160);

            SEOMeta::setTitle($title)
                    ->setDescription($desc);

            if ( $app->seo_keywords != '')
                SEOMeta:: setKeywords($app->seo_keywords);
            else
                SEOMeta::addKeyword( explode('-', str_slug($title) ));


            SEOMeta::addMeta('article:published_time', $app->created_at->toW3CString(), 'property');
            SEOMeta::addMeta('article:modified_time', $app->updated_at->toW3CString(), 'property');
            SEOMeta::addMeta('article:section', array_first($app->categories->pluck('title')), 'property');
            SEOMeta::addMeta('article:tag', $app->categories->pluck('title'), 'property');

            OpenGraph::setDescription($desc);
            OpenGraph::setTitle($title);
            OpenGraph::setUrl( $app->app_detail_url );
            OpenGraph::addProperty('type', 'article');
            OpenGraph::addProperty('locale', app()->getLocale());
            OpenGraph::addProperty('locale:alternate', ['en-us']);
            OpenGraph::addImage($app->app_image_url);


            // You can chain methods
            Twitter::setType('article')
                        ->setImage( $app->app_image_url )
                        ->setTitle($title)
                        ->setDescription($desc)
                        ->setUrl( $app->app_detail_url )
                        ->setSite($title);

            // $developer = $app->developer;
            // pre( $app->versions );
            // exit;
            return view('web.app.versions', $data);

        } catch (Exception $e) {
            logger()->debug($e);
            abort(404);
        }
    }

    public function getDownload( Request $request, $slug ) {

        $appModel = app(AppRepositoryEloquent::class);
        try {

            // https://apkcombo.com/google-play-store/app.buzz.share/download/apk
            $app = $appModel->makeModel()->bySlug( $slug)->first();

            $_data = $this->_commonDataForApp( $app );

            $similarToAppBasedOnCategory = collect();
            if ( !$app->categories->isEmpty()) {
                $categories = $app->categories->modelKeys();

                $similarToAppBasedOnCategory = $appModel->makeModel()->whereHas('categories', function ($q) use ($categories) {
                    $q->whereIn('categories.id', $categories);
                })->where('id', '<>', $app->id)->get()->take(self::DEFAULT_APP_LIMIT_DISPLAY)->unique();
            }

            $moreAppsFromDeveloper = collect();
            if ( !$app->developer->isEmpty()) {

                $getFirstDeveloper = $app->developer->first();
                if ( $getFirstDeveloper ) {
                    $getFirstDeveloper->load('apps');

                    $myapps                = $getFirstDeveloper->apps;
                    $moreAppsFromDeveloper = $myapps->where('id', '<>', $app->id)->take(self::DEFAULT_APP_LIMIT_DISPLAY)->unique();
                }
            }

            $data     = array_merge([
                'app' => $app,
                'similar_apps'   => $similarToAppBasedOnCategory,
                'developer_apps' => $moreAppsFromDeveloper,
                'developer'      => $getFirstDeveloper ?? '',
            ], $_data);


            SEOMeta::setTitle($app->seo_title . ' - '. dcmConfig('site_name'))
                    ->setDescription( str_limit( trim(strip_tags($app->description)), 160))
                    ->setKeywords($app->seo_keywords)
                    ->setCanonical( $app->app_detail_url );


            $title = $app->seo_title . ' - '. dcmConfig('site_name');
            $desc  = str_limit( trim(strip_tags($app->description)), 160);

            SEOMeta::setTitle($title)
                    ->setDescription($desc);

            if ( $app->seo_keywords != '')
                SEOMeta:: setKeywords($app->seo_keywords);
            else
                SEOMeta::addKeyword( explode('-', str_slug($title) ));


            SEOMeta::addMeta('article:published_time', $app->created_at->toW3CString(), 'property');
            SEOMeta::addMeta('article:modified_time', $app->updated_at->toW3CString(), 'property');
            SEOMeta::addMeta('article:section', array_first($app->categories->pluck('title')), 'property');
            SEOMeta::addMeta('article:tag', $app->categories->pluck('title'), 'property');

            OpenGraph::setDescription($desc);
            OpenGraph::setTitle($title);
            OpenGraph::setUrl( $app->app_detail_url );
            OpenGraph::addProperty('type', 'article');
            OpenGraph::addProperty('locale', app()->getLocale());
            OpenGraph::addProperty('locale:alternate', ['en-us']);
            OpenGraph::addImage($app->app_image_url);


            // You can chain methods
            Twitter::setType('article')
                        ->setImage( $app->app_image_url )
                        ->setTitle($title)
                        ->setDescription($desc)
                        ->setUrl( $app->app_detail_url )
                        ->setSite($title);


            return view('web.app.download', $data);

        } catch (Exception $e) {
            logger()->debug($e);
            abort(404);
        }
    }


    public function getSimilar( $slug   ) {

        $appModel     = app(AppRepositoryEloquent::class);
        try {

            $app   = $appModel->makeModel()->bySlug( $slug)->first();
            $_data = $this->_commonDataForApp( $app );

            $similarToAppBasedOnCategory = collect();
            if ( !$app->categories->isEmpty()) {
                $categories = $app->categories->modelKeys();
                $similarToAppBasedOnCategory = $appModel->makeModel()->whereHas('categories', function ($q) use ($categories) {
                    $q->whereIn('categories.id', $categories);
                })->where('id', '<>', $app->id)->get()->take(self::DEFAULT_APP_SIMILAR_DISPLAY)->unique();
                // ->paginate(self::DEFAULT_PAGINATION);
            }
            $data = array_merge([
                'app'            => $app,
                'similar_apps'   => $similarToAppBasedOnCategory
            ], $_data);
            $_app = $data['app'];

            SEOMeta::setTitle($_app->seo_title . ' - '. dcmConfig('site_name'))
                    ->setDescription( str_limit( trim(strip_tags($_app->description)), 160))
                    ->setKeywords($_app->seo_keywords)
                    ->setCanonical( $_app->app_detail_url );


            $title = $_app->seo_title . ' - '. dcmConfig('site_name');
            $desc  = str_limit( trim(strip_tags($_app->description)), 160);

            SEOMeta::setTitle($title)
                    ->setDescription($desc);

            if ( $_app->seo_keywords != '')
                SEOMeta:: setKeywords($_app->seo_keywords);
            else
                SEOMeta::addKeyword( explode('-', str_slug($title) ));


            SEOMeta::addMeta('article:published_time', $_app->created_at->toW3CString(), 'property');
            SEOMeta::addMeta('article:modified_time', $_app->updated_at->toW3CString(), 'property');
            SEOMeta::addMeta('article:section', array_first($_app->categories->pluck('title')), 'property');
            SEOMeta::addMeta('article:tag', $_app->categories->pluck('title'), 'property');

            OpenGraph::setDescription($desc);
            OpenGraph::setTitle($title);
            OpenGraph::setUrl( $_app->app_detail_url );
            OpenGraph::addProperty('type', 'article');
            OpenGraph::addProperty('locale', app()->getLocale());
            OpenGraph::addProperty('locale:alternate', ['en-us']);
            OpenGraph::addImage($_app->app_image_url);


            // You can chain methods
            Twitter::setType('article')
                        ->setImage( $_app->app_image_url )
                        ->setTitle($title)
                        ->setDescription($desc)
                        ->setUrl( $_app->app_detail_url )
                        ->setSite($title);

            // dd($data);
            return view('web.app.similar', $data);

        } catch (Exception $e) {
            logger()->debug($e);
            abort(404);
        }
    }

    public function postDownloadApk(Request $request) {

        $this->validate($request, [
            'app_id' => 'required|min:2',
            '_token' => 'required',
            'version' => 'required'
        ]);

        $appModel = app(AppRepositoryEloquent::class);
        try {

            $input = $request->all();
            $slug = decrypt($input['app_id'], true);

            if (!$slug)
                throw new Exception('App does not exists, Please try again.');

            $app = $appModel->makeModel()->bySlug( $slug )->first();
            if ( !$app )
                throw new Exception('App does not exists, Please try again.');

            if ( isset($input['version']) && $input['version'] != 'latest')
                return $this->_downloadVersion( $app, $input['version']);

            if ( $input['version'] === 'latest')  {

                // download from api source
                views($app)->collection('apk-downloaded')->record();
                return app(\App\Http\Controllers\Web\Api\ApkDownload::class)->download( $app->app_id );
            }

        } catch (Exception $e) {
            logger()->debug($e);
            abort(404);
        }
    }

    private function _commonDataForApp( $app ) {


        // sort versions based on number.
        $versions = $app->versions->toArray();
        if ( $versions )
            columnSort($versions, ['identifier', 'desc']);

        $latestVersion  = array_first($versions);
        $data  = [
            'versions'                   => $versions,
            'latest_version'             => $latestVersion['identifier'] ?? '',
            'latest_version_size'        => $latestVersion['size_formatted'] ?? '0KB',
            'latest_version_changelog'   => $latestVersion['description'] ?? '',
            'latest_version_created_at'  => $latestVersion['created_at'] ?? '',
            'latest_version_description' => $latestVersion['description'] ?? ''
        ];

        return $data;

    }


    private function _downloadVersion( $app,  $versionHashId ) {

        $versionHashId = hasher($versionHashId, true);

        if ( !$versionHashId ) {
            logger()->info('No version found from this url: ' . request()->fullUrl());
            return redirect()->to( $app->app_detail_url);
        }

        $appVersionModel = app(AppVersionRepositoryEloquent::class);
        $versionModel    = $appVersionModel->find( $versionHashId );

        if($versionModel->is_link_external == 1)
            return redirect()->to($versionModel->download_link);
        else {

            $storage = Storage:: disk('apk-uploads');

            if (  $storage->exists($versionModel->file_path) ) {

                $path      = @pathinfo($versionModel->file_path);
                $extension = $path['extension'] ?? 'apk';

                $fileName  = str_slug($app->title).'-'.$versionModel->identifier.'-'.str_slug(dcmConfig('site_name')).'.'.$extension;

                $header = [
                    'Content-Description' => 'File Transfer',
                    'Content-Type'        => 'application/octet-stream'
                ];
                return $storage->download($versionModel->file_path, $fileName, $header);
            } else {
                logger()->info('File path doesnt exists: ' . $versionModel->file_path);
                return redirect()->to( $app->app_detail_url);
            }
        }
        logger()->info('Does not meet any condition redirecting back to: ' .$app->app_detail_url);
        return redirect()->to( $app->app_detail_url);
    }

}