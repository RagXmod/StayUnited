<?php

namespace App\App\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use CyrildeWit\EloquentViewable\Viewable;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;
use Prettus\Repository\Contracts\Transformable;
use App\App\Eloquent\Observers\CategoryObserver;
use Prettus\Repository\Traits\TransformableTrait;


use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Category.
 *
 * @package namespace App\App\Eloquent\Entities;
 */
class Category extends Model implements Transformable, ViewableContract, Searchable
{
    use TransformableTrait, Viewable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status_identifier',
        'identifier',
        'slug',
        'title',
        'description',
        'seo_title',
        'seo_keyword',
        'seo_description',
        'icon',
        'is_enabled',
        'is_featured',
        'views',
        'is_demo',
    ];


    protected $appends = [
        'value',
        'label',
        'status_label',
        'page_url'
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

    public function getSeoKeywordAttribute( $value ) {
        return commaStringToArrayKeywords($value);
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

    public function getPageUrlAttribute() {
        return hasRoute('web.app.category.detail') ?  route('web.app.category.detail',$this->slug) : '1#';
    }


    public function scopeIsActive($query,$type = 'active') {
        return $query->where('status_identifier',$type);
    }

    public function getLabelAttribute() {
        return $this->title;
    }

    public function getValueAttribute() {
        return $this->identifier;
    }

    public function apps()
    {
        return $this->morphedByMany('App\App\Eloquent\Entities\App', 'categoreable');
    }


    public function cacheKeyArray($key = null) {
        $data = [
            'tbl_name'                       => $this->getTable(),
            'category_app_options_cache_key' => 'category_app_options_cache_key',
        ];

        if($key)
            return isset($data[$key]) ? $data[$key] : '';

        return $data;
    }

    protected static function boot() {
        parent::boot();

        Category::observe(new CategoryObserver());
    }

}
