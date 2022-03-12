<?php

namespace Modules\Configuration\Eloquent\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\Core\Traits\RepositoryEloquentTrait;
use Prettus\Repository\Criteria\RequestCriteria;
use Modules\Configuration\Eloquent\Entities\Configuration;
use Modules\Configuration\Eloquent\Validators\ConfigurationValidator;
use Modules\Configuration\Eloquent\Interfaces\ConfigurationRepository;
use Modules\Configuration\Eloquent\Transformers\ConfigurationTransformer;
use Exception;
/**
 * Class ConfigurationRepositoryEloquent.
 *
 * @package namespace Modules\Configuration\Eloquent\Repositories;
 */
class ConfigurationRepositoryEloquent extends BaseRepository implements ConfigurationRepository
{
    use RepositoryEloquentTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Configuration::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function getAllConfigurations($resetkeys = true) {

        $cacheKey = $this->model->cacheKeyArray('tbl_name');
        return $this->cacheCollections($cacheKey, new ConfigurationTransformer, $resetkeys,'allconfigurations');
    }


    public function findByIdentifier( $identifier ) {
        $data = $this->getAllConfigurations(false);
        if( !isset($data[$identifier]) )
            return null;
        return $data[$identifier];
    }


    public function findByGroup( $groupIdentifier ) {
        $data = $this->getAllConfigurations(true);

        $_collections = array_where($data, function( $item ) use($groupIdentifier) {
            return $item['group'] === $groupIdentifier;
        });

        $collect = [];
        foreach( $_collections as &$_item) {

            if ($_item['identifier'] === 'meta_keywords')
                $_item['value'] = commaStringToArrayKeywords($_item['value']);

            $collect[ $_item['identifier'] ] =  $_item['value'];
        }
        return $collect;
    }

}
