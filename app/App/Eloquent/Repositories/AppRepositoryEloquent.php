<?php

namespace App\App\Eloquent\Repositories;

use Cache;
use Exception;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;
use App\App\Eloquent\Entities\App;
use League\Fractal\Resource\Collection;
use Spatie\Searchable\ModelSearchAspect;
use App\App\Eloquent\Interfaces\AppRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Modules\Core\Traits\RepositoryEloquentTrait;
use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Transformers\AppTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

/**
 * Class AppRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class AppRepositoryEloquent extends BaseRepository implements AppRepository
{

    use RepositoryEloquentTrait;

    private $perPage = 20;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return App:: class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    /**
     * Search from android markets
     */
    public function search($query)
    {

        $searchResults = (new Search())
            ->registerModel( $this->model(), function(ModelSearchAspect $modelSearchAspect) {
                    $modelSearchAspect
                    ->addSearchableAttribute('title')
                    ->addSearchableAttribute('app_id');
                    // ->addExactSearchableAttribute('app_id'); exact match
            })
            ->registerModel( \App\App\Eloquent\Entities\Category::class, 'title')
            ->search($query);

        return $searchResults;
    }

    /**
     * Get detailed info by APP id
     */
    public function findByAppSlug($slug)
    {
        $appModel = $this->model
                        ->with(['developer','categories', 'moreDetails', 'versions','screenshots','tags'])
                        ->isActive()
                        ->bySlug($slug);

        $dataModel = $appModel->first();
        if(!$dataModel)
            throw new Exception("{$slug} does not exists in our database. Please contact us to add it for you.");

        return $dataModel;

    }

     /**
     * Get detailed info by APP id
     */
    public function findById($id)
    {
        $data = $this->model
                        ->with(['categories', 'moreDetails', 'versions','screenshots','tags'])
                        ->find($id);

        if(!$data)
            throw new Exception("{$id} does not exists in our database. Please contact us to add it for you.");


        $_data = $data->toArray();

        if ( $_data['seo_keyword'] )
            $_data['seo_keyword'] = commaStringToArrayKeywords($_data['seo_keyword']);

        $_data['pageindex'] = route('admin.app.index');

        // sort versions based on number.
        columnSort($_data['versions'], ['identifier', 'desc']);

        if ( isset($_data['tags'])) {

            $tags = [];
            foreach($_data['tags'] as $tag) {
                $tags[] = $tag['name']['en'] ?? '';
            }
            $_data['tags'] = array_filter($tags);
        }
        return $_data;

    }


    /**
     * Get detailed info by APP id
     */
    public function appCollections(Request $request)
    {

        $m = $this->model->query();

        if( $request->has('letter') && $request->input('letter') != '') {
            if ( !in_array($request->input('letter'), ['All', 'all']))
                $m->where('title','LIKE',$request->input('letter').'%');
        }

        if( $request->input('q') )
            $m->where('title','LIKE','%'.$request->input('q').'%')
                ->orWhere('app_id','LIKE','%'.$request->input('q').'%');;

        if( $request->input('per_page') )
            $this->perPage = $request->input('per_page');

        if( $request->has('status') && $request->input('status') != '')
            $m->where('status_identifier', $request->input('status'));

        $paginator = $m->orderBy('created_at','desc')->paginate($this->perPage);
        $apps      = $paginator->items();

        $resource = $this->transformerByCollection($apps, new AppTransformer , false);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        $data = $this->fractalCreateData($resource);
        return $data;
    }


    public function statusCollections( $identifier = '')
    {

        $statusKey   = $this->model->cacheKeyArray('app_status_cache_key');
        $that        = $this;
        $collections = cache()->rememberForever( $statusKey, function () use( $that ) {
            $items = $that->statusArr();

            $collections = [];
            foreach( $items as $item) {

                             $identifier  = str_slug($item,'_');
                $collections[$identifier] = [
                    'label'    => $item,
                    'value'    => $identifier,
                    'selected' => ($identifier === 'active') ? true : false,
                ];
            }
            return $collections;
        });
        if ( $identifier)
            return isset($collections[$identifier]) ? $collections[$identifier]: $collections['active'];

        return array_values($collections);
    }

    public function statusArr() {
        return [
            'Active',
            'In active',
        ];
    }


    public function getNavigations() {

        return [
            [
                'title' => 'All Apps',
                'link'  => route('admin.app.index'),
                'fa'    => 'fab',
                'icon'  => 'android'
            ],
            [
                'title' => 'New App',
                'link'  => route('admin.app.create'),
                'sub_title' => 'Manual create app',
                'icon'  => 'plus-circle'
            ],
            [
                'title' => 'Google Play Store',
                'link'  => route('admin.app.create.from.store'),
                'sub_title' => 'Create via api',
                'fa'    => 'fab',
                'icon'  => 'google-play'
            ],
            [
                'title' => 'Setup Featured Apps',
                'link'  => route('admin.featured.apps'),
                'icon'  => 'mobile-alt'
            ],
            // [
            //     'title'       => 'Submitted Apps',
            //     'link'        => '#',
            //     'is_disabled' => 'disabled',
            //     'icon'        => 'users'
            // ],
        ];
    }

    public function newestApps( $limit = 12) {
        return $this->model->IsActive()->orderBy('created_at','desc')->take($limit)->get();
    }
}
