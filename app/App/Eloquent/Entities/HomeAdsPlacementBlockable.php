<?php

namespace App\App\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\App\Eloquent\Observers\HomeAdsPlacementBlockableObserver;

/**
 * Class HomeAdsPlacementBlockable.
 *
 * @package namespace App\App\Eloquent\Entities;
 */
class HomeAdsPlacementBlockable extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    public function cacheKeyArray($key = null) {
        $data = [
            'tbl_name' => $this->getTable(),
            'ads_collections' => 'ads_collections',
        ];

        if($key)
            return isset($data[$key]) ? $data[$key] : '';

        return $data;
    }


    protected static function boot() {
        parent::boot();

        HomeAdsPlacementBlockable::observe(new HomeAdsPlacementBlockableObserver());
    }
}
