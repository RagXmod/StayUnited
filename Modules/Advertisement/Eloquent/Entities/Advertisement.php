<?php

namespace Modules\Advertisement\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Modules\Advertisement\Eloquent\Observers\AdvertisementObserver;

/**
 * Class Advertisement.
 *
 * @package namespace Modules\Advertisement\Eloquent\Entities;
 */
class Advertisement extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identifier',
        'title',
        'ads_code',
        'is_enabled'
    ];


    
    public function cacheKeyArray($key = null) {
        $data = [
            'tbl_name' => $this->getTable()
        ];

        if($key)
            return isset($data[$key]) ? $data[$key] : '';

        return $data;
    }


    protected static function boot() {
        parent::boot();

        Advertisement::observe(new AdvertisementObserver());
    }


}
