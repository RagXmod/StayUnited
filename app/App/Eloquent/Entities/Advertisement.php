<?php

namespace App\App\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\App\Eloquent\Observers\AdvertisementObserver;

/**
 * Class Advertisement.
 *
 * @package namespace App\App\Eloquent\Entities;
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


    public function ads()
    {
        return $this->morphToMany(\App\App\Eloquent\Entities\HomeAdsPlacementBlock::class, 'home_ads_placement_blockables');
    }


    protected static function boot() {
        parent::boot();

        Advertisement::observe(new AdvertisementObserver());
    }


}
