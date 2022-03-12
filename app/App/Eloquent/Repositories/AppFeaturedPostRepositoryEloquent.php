<?php

namespace App\App\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\App\Eloquent\Interfaces\AppFeaturedPostRepository;
use App\App\Eloquent\Entities\AppFeaturedPost;
use Exception;
use Cache;
/**
 * Class AppFeaturedPostRepositoryEloquent.
 *
 * @package namespace App\App\Eloquent\Repositories;
 */
class AppFeaturedPostRepositoryEloquent extends BaseRepository implements AppFeaturedPostRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AppFeaturedPost::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

     /**
     * Get detailed info by APP id
     */
    public function findById($id)
    {
        $data = $this->model
                        ->with(['apps'])
                        ->find($id);

        if(!$data)
            throw new Exception("{$id} does not exists in our database. Please contact us to add it for you.");


        $_data = $data->toArray();

        if ( $_data['seo_keyword'] )
            $_data['seo_keyword'] = commaStringToArrayKeywords($_data['seo_keyword']);

        $_data['pageindex'] = route('admin.app.index');


        // pre($_data);
        // exit;
        return $_data;

    }


     /**
     * Get detailed info by APP id
     */
    public function findBySlug($slug)
    {
        $data = $this->model
                    ->bySlug($slug)
                    ->first();

        if(!$data)
            throw new Exception("{$slug} does not exists in our database. Please contact us to add it for you.");


        $apps = $data->apps()->paginate(4);
        return [
            'apps'         => $apps,
            'featured_app' => $data
        ];
    }


    public function getAllActiveFeaturedPosts() {

        $myCacheKey = $this->model->cacheKeyArray('all_active_featured_post_ids');
        $that       = $this;
        $featuredPostCollections  = cache()->remember( $myCacheKey, 1440, function () use( $that ) {
            return $that->model->isActive()->get()->toArray();

        });

        $appIds    = array_pluck($featuredPostCollections, 'id');
        $stringIds = implode(',' , $appIds);


        if ( $stringIds )  {

            $_collection = $this->showFeaturedAppByIds( $stringIds );

            foreach($featuredPostCollections as &$item) {

                if (isset($_collection[$item['id']])) {
                    $item['apps'] = $_collection[$item['id']];
                }
            }
        }
        return $featuredPostCollections;
    }


    public function showFeaturedAppByIds($appIds) {

        $appFeaturedPostable = env('DB_PREFIX').'app_featured_postables';
        $appsTable = env('DB_PREFIX').'apps';

        $sqlRaw = "SELECT `{$appsTable}`.`app_id`,
                            `{$appsTable}`.`title`,
                            `{$appsTable}`.`slug`,
                            `{$appsTable}`.`short_description`,
                            `{$appsTable}`.`description`,
                            `{$appsTable}`.`app_image_url`,
                            `{$appsTable}`.`current_ratings`,
                        `{$appFeaturedPostable}`.`app_featured_post_id` AS `pivot_app_featured_post_id`,
                        `{$appFeaturedPostable}`.`position` AS `pivot_position`,
                        `{$appFeaturedPostable}`.`app_featured_postable_id` AS `pivot_app_featured_postable_id`,
                        `{$appFeaturedPostable}`.`app_featured_postable_type` AS `pivot_app_featured_postable_type`
                    FROM `{$appsTable}`
                    INNER JOIN `{$appFeaturedPostable}` ON `{$appsTable}`.`id` = `{$appFeaturedPostable}`.`app_featured_postable_id`
                    WHERE `status_identifier` = 'active'
                    AND `{$appFeaturedPostable}`.`app_featured_post_id` IN ({$appIds});";

        // pre($sqlRaw);exit;
        $queryResult = \DB::select(\DB::raw($sqlRaw));
        $_collection = collect($queryResult)->each(function($item){
            // customurl here
            $item->app_detail_url   = route('web.app.detail',$item->slug);
            $item->app_download_url = route('web.app.detail.download', $item->slug);

        })->sortBy('pivot_position')->groupBy('pivot_app_featured_post_id');

        return $_collection;
    }

}
