<?php

namespace Modules\Core\Traits;

use Cache;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;

/**
 * Module Core Providers: Modules\Core\Traits\RepositoryEloquentTrait
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

trait RepositoryEloquentTrait {

    public $cacheTTL = 1440; //24hours
    public $cache_prefix   = 'dcm_tbl';

    private $isCache         = false;
    private $rememberForever = true;
    private $instanceOfSerializer;
    private $instanceOfTransformer;
    private $instanceOfCursor;
    private $excludeItem,
            $includeItem;


    public function getDistinctIds($model, $field, $isQuery = false) {

        $queries = $model->distinct()->select($field);

        if ( $isQuery === true )
            return $queries->get();

        $ids = [];
        foreach ($queries->cursor() as $query)
            $ids[] = $query->{$field};

        return $ids;

    }

	/**
     * CACHE
     */
    public function cachemodel()
    {
        $_model    = $this->model;
        $cacheKey = $_model->getTable();

        $cacheKey  = "{$this->cache_prefix}:{$cacheKey}";

        return Cache::remember( $cacheKey, $this->cacheTTL, function() use ($_model) {
            $items = $_model->where('id','>', 0)->cursor();
            $data    = [];
            foreach ($items as $key => $item) {

                $_item = $item->toArray();
                $_data = [];
                foreach ($_item as $key => $value) {

                    switch ($key) {
                        case 'position':
                            $value = floatval($value);
                            break;
                    }

                    if ( $value && !is_array($value) ) {
                        $json_details = json_decode($value, true);
                        if ( is_array($json_details) )
                            $value = $json_details;
                        // else {
                        //     try {
                        //         $value = now()->createFromFormat('Y-m-d H:i:s', $value);
                        //     }
                        //     catch(InvalidArgumentException $e) {}
                        // }
                    }
                    $_data[$key] = $value;
                }
                $data[] = $_data;
            }
            return collect($data);
        });

    }

    public function collection($where = [], $columns = ['id'], $raw = false)
    {

        $query = $this->model;

        if ( $columns )
            $query = $query->select($columns);

        if ( $where ) {
            if ( is_array($where) ) {
                foreach ($where as $key => $value) {
                    $query = $query->where($key, $value);
                }
            }
            else
                $query = $query->where($key, $value);
        }

        if ( $raw === true )
            return $query;

        return $query->get();
    }


    public function transformerByCollection($model, $transformer, $type = '')
    {
        return new Collection($model, $transformer,$type);
    }

    public function transformerByItem($model, $transformer, $type = '')
    {
        return new Item($model, $transformer,  $type = '');
    }

    public function fractalCreateData($resource, $includeName = null, $returnAsResource = false)
    {
        $fractal  = new Manager();

        if(!$this->instanceOfSerializer)
            $this->instanceOfSerializer = new ArraySerializer();

        if( $includeName ) {
            $this->setInclude($includeName);
            $fractal->parseIncludes($this->includeItem);
        }

        if ( $this->excludeItem )
            $fractal->parseExcludes($this->excludeItem);

        $fractal->setSerializer( $this->instanceOfSerializer );

        if ( $this->instanceOfCursor )
            $resource->setCursor($this->instanceOfCursor);

        if($returnAsResource == true)
            return $fractal->createData($resource);

        return $fractal->createData($resource)->toArray();
    }

    public function setFractalSerializer($newInstance = false)
    {
        if($newInstance != false)
            $this->instanceOfSerializer = $newInstance;
        return $this;
    }

    public function setCustomTransformer($transformer = false)
    {
        if($transformer != false)
            $this->instanceOfTransformer = $transformer;

        return $this;
    }

    public function setInclude($includeItem = null)
    {
        $_includeItem = $includeItem;
        if ( is_array($includeItem))
            $_includeItem = implode(',', $includeItem);

        $this->includeItem = $_includeItem;
        return $this;
    }


    public function setCursor($currentCursor = null, $previousCursor = null, $newCursorId, $counter = 0)
    {
        $this->instanceOfCursor = new Cursor($currentCursor, $previousCursor, $newCursorId, $counter);
        return $this;
    }



    public function setExclude($excludeItem = null)
    {
        $_excludeItem = $excludeItem;
        if ( is_array($excludeItem))
            $_excludeItem = implode(',', $excludeItem);

        $this->excludeItem = $_excludeItem;
        return $this;
    }

    // for collections only..
    public function cacheCollections($cacheKey, $transformerInstance, $resetKeys = true, $options = null) {

        $cacheKey  = "{$this->cache_prefix}:{$cacheKey}";
        if( in_array(env('CACHE_DRIVER','file'),['file','array','database']) ) {
            $cacheData =  Cache::remember($cacheKey, $this->cacheTTL, function() use($transformerInstance,$options) {

                // Get All Collections
                $collections    = $this->collection([],['*'],true)->cursor();
                $lists  = [];
                foreach($collections as $index => $item)
                {
                    $identifier  = isset($item->identifier) ? $item->identifier : $index;
                    $resource    = $this->transformerByItem($item, $transformerInstance , false);
                    $lists[$identifier] = $this->fractalCreateData($resource, $options);
                }
                return $lists;
            });
        }
        else {
            $cacheData =  Cache::tags($cacheKey)->remember($cacheKey, $this->cacheTTL,function() use($transformerInstance,$options) {

                // Get All Collections
                $collections    = $this->collection([],['*'],true)->cursor();
                $lists  = [];
                foreach($collections as $index => $item)
                {
                    $identifier  = isset($item->identifier) ? $item->identifier : $index;
                    $resource    = $this->transformerByItem($item, $transformerInstance , false);
                    $lists[$identifier] = $this->fractalCreateData($resource, $options);
                }
                return $lists;
            });
        }

        if( $resetKeys === true)
            return array_values($cacheData);
        return $cacheData;
    }

}