<?php

namespace App\App\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\App\Eloquent\Observers\AppDeveloperObserver;

use CyrildeWit\EloquentViewable\Viewable;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;

/**
 * Class AppDeveloper.
 *
 * @package namespace App\App\Eloquent\Entities;
 */
class AppDeveloper extends Model implements Transformable, ViewableContract
{
    use TransformableTrait, Viewable;

    protected $removeViewsOnDelete = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'identifier',
        'slug',
        'url',
        'title',
        'description',
        'details'
    ];


    protected $appends = [
        'value',
        'label',
        'developer_detail_url'
    ];

    public function getLabelAttribute() {
        return $this->title;
    }

    public function getValueAttribute() {
        return $this->identifier;
    }


    public function getDeveloperDetailUrlAttribute() {
        return route('web.app.developer.detail',$this->slug);
    }

    public function apps()
    {
        return $this->belongsToMany(\App\App\Eloquent\Entities\App::class, 'apps_developers')->withTimestamps();
    }


    public function scopeByIdentifier($query,$identifier) {
        return $query->where('identifier',$identifier);
    }


    public function scopeBySlug($query,$slug) {
        return $query->where('slug',$slug);
    }

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

        AppDeveloper::observe(new AppDeveloperObserver());
    }
}
