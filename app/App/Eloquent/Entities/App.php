<?php

namespace App\App\Eloquent\Entities;

use Laravelista\Comments\Commentable;
use Illuminate\Database\Eloquent\Model;
use App\App\Eloquent\Observers\AppObserver;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Spatie\Tags\HasTags;
use Modules\Review\Eloquent\Traits\HasReviews;
use CyrildeWit\EloquentViewable\Viewable;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;


use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class App.
 *
 * @package namespace App\App\Eloquent\Entities;
 */
class App extends Model implements Transformable, ViewableContract, Searchable
{
    use TransformableTrait, Commentable, HasTags, HasReviews, Viewable;


    protected $removeViewsOnDelete = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'app_id',
        'status_identifier',
        'title',
        'slug',
        'short_description',
        'description',
        'app_link',
        'app_image_url',
        'current_ratings',
        'total_ratings',
        'seo_title',
        'seo_keyword',
        'seo_description',
        'is_cron_check'
    ];


    protected $appends = [
        'admin_detail_url',
        'app_detail_url',
        'app_download_url',
        'value',
        'label'
    ];

    public function getSearchResult(): SearchResult
    {
        $url = route('web.app.detail', $this->slug);
        return new \Spatie\Searchable\SearchResult(
           $this,
           $this->title,
           $url
        );
    }

    public function getLabelAttribute() {
        return $this->title;
    }

    public function getValueAttribute() {
        return $this->app_id;
    }


    public function getAdminDetailUrlAttribute() {
        return route('admin.app.detail',$this->id);
    }

    public function getAppDetailUrlAttribute() {
        return route('web.app.detail', $this->slug);
    }

    public function getAppDownloadUrlAttribute() {
        return route('web.app.detail.download', $this->slug);
    }


    public function getAppImageUrlAttribute($imageUrl) {

        $image = asset('img/default-app.png');
        if ( $imageUrl )  {
            return $imageUrl;
        } else {
            return $this->appImage->image_link ?? $image;
        }
    }

    public function scopeIsActive($query,$type = 'active') {
        return $query->where('status_identifier',$type);
    }

    public function scopeByAppId($query,$appId) {
        return $query->where('app_id',$appId);
    }

    public function scopeBySlug($query,$slug) {
        return $query->where('slug',$slug);
    }

    public function developers()
    {
        return $this->belongsToMany(\App\App\Eloquent\Entities\AppDeveloper::class, 'apps_developers');
    }

    public function developer()
    {
        return $this->belongsToMany(\App\App\Eloquent\Entities\AppDeveloper::class, 'apps_developers')->take(1);
    }

    public function versions()
    {
        return $this->hasMany(\App\App\Eloquent\Entities\AppVersion::class);
    }

    public function screenshots()
    {
        return $this->morphMany(\App\App\Eloquent\Entities\AppImage::class, 'app_imageable')->whereIn('upload_type',['screenshots','link']);
    }

    public function appImage()
    {
        return $this->morphOne(\App\App\Eloquent\Entities\AppImage::class, 'app_imageable')->where('upload_type', 'app_image');
    }

    public function categories()
    {
        return $this->morphToMany(\App\App\Eloquent\Entities\Category::class, 'categoreable');
    }


    public function moreDetails()
    {
        return $this->hasMany(\App\App\Eloquent\Entities\AppMoreDetail::class);
    }


    public function cacheKeyArray($key = null) {
        $data = [
            'tbl_name'             => $this->getTable(),
            'app_status_cache_key' => 'app_status',
            'app_identifier_key' => 'app_identifier_',
        ];

        if($key)
            return isset($data[$key]) ? $data[$key] : '';

        return $data;
    }


    protected static function boot() {
        parent::boot();

        App::observe(new AppObserver());
    }
}
