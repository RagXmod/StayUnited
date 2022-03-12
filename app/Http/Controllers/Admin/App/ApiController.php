<?php

namespace App\Http\Controllers\Admin\App;

use Exception;
use Illuminate\Http\Request;
use Facades\App\App\Facades\ApiFacade;
use Modules\Core\Traits\ResponseTrait;

use Modules\Core\Http\Controllers\BaseController;
use App\App\Eloquent\Repositories\AppRepositoryEloquent;
use App\App\Eloquent\Repositories\CategoryRepositoryEloquent;

use App\App\Eloquent\Transformers\ApiSearchTransformer;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use DB;


class ApiController extends BaseController
{
    use ResponseTrait;

    CONST API_PERPAGE = 25;

    public function __construct(AppRepositoryEloquent $appModel,
                        CategoryRepositoryEloquent $categoryModel)
    {

        parent:: __construct();

       $this->appModel      = $appModel;
       $this->categoryModel = $categoryModel;
    }


    public function getSearch(Request $request) {

        $request->validate([
            'q' => 'required',
        ]);

        try {

            $query  = $request->get('q');
            $page   = $request->get('page', 1);

            $result    = ApiFacade::search($query);
            $paginator = paginateCollection($result, self::API_PERPAGE, $page);

            $fractal = new Manager();
            $resource = new Collection($paginator, function(array $item) {
                return $item;
            });

            $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
            $array = $fractal->createData($resource)->toArray();

            return $this->success($array);

        } catch (Exception $e) {
            logger()->debug($e);
            return $this->failed($e->getMessage());
        }
    }

    public function postAppDetail(Request $request) {

        $request->validate([
            'app_id' => 'required',
        ]);

        try {

            $appId  = $request->get('app_id');
            $result = ApiFacade::detail($appId, [], 'admin');

            return $this->success($result);

        } catch (Exception $e) {
            logger()->debug($e);
            return $this->failed($e->getMessage());
        }

    }

    public function postCreateAppsFromSearch(Request $request) {

        $request->validate([
            'apps'       => 'required',
            'categories' => 'required'
        ]);


        try {

            $input = $request->all();
            $input = array_filter($input);

            $apps       = $input['apps'];
            $appIdArray = array_pluck($apps, 'app_id');

            $existingAppCollections = $this->appModel->makeModel()->whereIn('app_id', $appIdArray)->get();
            $collectionIds          = $existingAppCollections->pluck('app_id');
            $collectOne             = collect( $appIdArray );
            $collectDiff            = $collectOne->diff( $collectionIds->toArray() );
            $collectDiffArray       = $collectDiff->all();

            DB:: beginTransaction();

                $user   = $this->auth->user();
                $userId = $user->id ?? 1;

                foreach($apps as $app) {

                    // skip duplicate items.
                    if ( !in_array($app['app_id'] ?? '', $collectDiffArray) )
                        continue;

                    $title = $app['title'] ?? '';
                    $app['slug'] = str_slug($app['slug'] ?? $title);

                    if ( !isset($app['status_identifier']))
                        $app['status_identifier'] = 'active';

                    $app['user_id']          = $userId;
                    $app['app_link']          = $app['app_url'];
                    $app['app_image_url']     = $app['image_url'];
                    $app['current_ratings']   = number_format((float) ($app['score'] ?? 0), 2, '.', '');
                    $app['total_ratings']     = number_format(str_replace('.',  '', $app['score'] ?? 0), 0 );
                    $app['short_description'] = $app['description'] = $title;
                    $app['seo_title']         = $title;
                    $app['seo_description']   = $app['summary'] ?? $title;
                    $app['seo_keyword']       = arrayKeywordsToCommaString( explode(' ', $title) );
                    $app['is_cron_check']     = 1;


                    $appModel = $this->appModel->makeModel()->fill($app);
                    $appModel->save();

                    if($appModel) {

                    }
                }

            DB:: commit();

            // pre($input);
            // exit;
            return $this->success('Successfully create new apps. Waiting for cron to run.');

        } catch (Exception $e) {
            logger()->debug($e);
            DB:: rollback();
            return $this->failed($e->getMessage());
        }
    }

}