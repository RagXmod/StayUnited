<?php

namespace App\App\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\App\Eloquent\Observers\AppFeaturedPostObserver;

/**
 * Class AppFeaturedPost.
 *
 * @package namespace App\App\Eloquent\Entities;
 */
class AppFeaturedPost extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'status_identifier',
        'slug',
        'description',
        'seo_title',
        'seo_keyword',
        'seo_description',
        'icon'
    ];

    public $appends = [
        'status_label',
        'more_url'
    ];



    public function getMoreUrlAttribute() {
        return route('web.home.latest', $this->slug);
    }

    public function apps()
    {
        return $this->morphedByMany('App\App\Eloquent\Entities\App', 'app_featured_postable');
    }

    public function activeApps()
    {
        return $this->morphedByMany('App\App\Eloquent\Entities\App', 'app_featured_postable')->isActive();
    }

    public function scopeIsActive($query,$type = 'active') {
        return $query->where('status_identifier',$type);
    }


    public function scopeBySlug($query,$slug) {
        return $query->where('slug',$slug);
    }


    public function getStatusLabelAttribute() {

        switch ($this->status_identifier) {
            case 'active':
                return 'Active';
                break;
            case 'in_active':
                return 'In Active';
                break;
            default:
                return 'Active';
                break;
        }
    }

    public function cacheKeyArray($key = null) {
        $data = [
            'tbl_name'                     => $this->getTable(),
            'all_active_featured_post_ids' => 'all_active_featured_post_ids',
        ];

        if($key)
            return isset($data[$key]) ? $data[$key] : '';

        return $data;
    }

    protected static function boot() {
        parent::boot();

        AppFeaturedPost::observe(new AppFeaturedPostObserver());
    }
}
