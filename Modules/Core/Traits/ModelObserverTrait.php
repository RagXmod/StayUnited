<?php

namespace Modules\Core\Traits;

/**
 * Module Core Providers: Modules\Core\Traits\ModelObserverTrait
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

use Cache;

trait ModelObserverTrait
{

    public $prefix           = 'dcm_tbl';
    public $additionalKeyArr = [];

    /**
     * @return mixed
    */
    public function removeCache( $key )
    {
        if( in_array(env('CACHE_DRIVER','file'),['file','array','database']) ) {
            Cache::forget($key);
        } else {
            Cache::tags($key)->flush();
        }
    }

    /*
     *
    */
    public function tableCache($model)
    {
        $table   = $model->getTable();

        $collect = $this->tableArr( $table );
        if ( $this->additionalKeyArr )
            $collect = array_merge($collect, $this->additionalKeyArr);

        foreach($collect as $tbl) {
            Cache::forget($tbl);
            if( !in_array(env('CACHE_DRIVER','file'),['file','array','database']) )
                Cache::tags($tbl)->flush();
        }
    }

    public function pushNewTableCacheName( array $cacheName = [] ) {
        $this->additionalKeyArr = $cacheName;
        return $this;
    }

    private function tableArr( $table ) {
        return [
            "table:{$table}",
            "{$this->prefix}_{$table}",
            "{$this->prefix}:{$table}",
            $table
        ];
    }

}