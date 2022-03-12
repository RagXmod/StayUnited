<?php

namespace App\App\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\App\Eloquent\Observers\HomeSliderObserver;

use Storage;

/**
 * Class HomeSlider.
 *
 * @package namespace App\App\Eloquent\Entities;
 */
class HomeSlider extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'link',
        'name',
        'path',
        'size',
        'position'
    ];

    protected $appends = [
        'image_url',
    ];


    public function getImageUrlAttribute($imageUrl) {
        $image = 'https://via.placeholder.com/850x400?text=Image%20Here';
        if ( $imageUrl )  {
            return $imageUrl;
        } else {
            $image =  Storage::disk('slider-uploads')->url($this->path);
            return $image;
        }
    }

    public function cacheKeyArray($key = null) {
        $data = [
            'tbl_name' => $this->getTable(),
        ];

        if($key)
            return isset($data[$key]) ? $data[$key] : '';

        return $data;
    }


    protected static function boot() {
        parent::boot();

        HomeSlider::observe(new HomeSliderObserver());
    }

}
