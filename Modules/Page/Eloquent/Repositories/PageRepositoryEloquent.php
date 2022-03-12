<?php

namespace Modules\Page\Eloquent\Repositories;

use Modules\Page\Eloquent\Entities\Page;
use Prettus\Repository\Eloquent\BaseRepository;
use Modules\Core\Traits\RepositoryEloquentTrait;
use Prettus\Repository\Criteria\RequestCriteria;
use Modules\Page\Eloquent\Interfaces\PageRepository;
use Modules\Page\Eloquent\Transformers\PageTransformer;

/**
 * Class PageRepositoryEloquent.
 *
 * @package namespace Modules\Page\Eloquent\Repositories;
 */
class PageRepositoryEloquent extends BaseRepository implements PageRepository
{

    use RepositoryEloquentTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Page::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function getAllPages($resetkeys = true) {

        $cacheKey = $this->model->cacheKeyArray('tbl_name');
        return $this->cacheCollections($cacheKey, new PageTransformer, $resetkeys);
    }


    public function findByIdentifier( $identifier ) {
        $data = $this->getAllPages(false);
        if ( is_array($identifier) ) {
            return collect($data)
                ->whereIn('identifier', $identifier)
                ->toArray();
        }
        if( !isset($data[$identifier]) )
            return null;
        return $data[$identifier];
    }


    public function getPageUrlByIdentifier( $identifier ) {
        $data = $this->getAllPages(false);
        if( !isset($data[$identifier]) )
            return '#';
        return $data[$identifier]['link'] ?? '#';
    }


    public function findByPageSlug( $slug ) {

        $data = $this->getAllPages(false);

        $getMyPage = array_first(array_where($data, function($item) use($slug) {
            return $item['slug'] === $slug;
        }));
        return $getMyPage;
    }

}
