<?php

namespace App\App\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\App\Eloquent\Observers\HomeAdsPlacementBlockObserver;

/**
 * Class HomeAdsPlacementBlock.
 *
 * @package namespace App\App\Eloquent\Entities;
 */
class HomeAdsPlacementBlock extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'identifier'
    ];

    public function ads()
    {
        return $this->morphedByMany('App\App\Eloquent\Entities\Advertisement', 'home_ads_placement_blockable');
    }

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

        HomeAdsPlacementBlock::observe(new HomeAdsPlacementBlockObserver());
    }
}
