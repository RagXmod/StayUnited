<?php

namespace Modules\Category\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Modules\Category\Eloquent\Observers\CategoryObserver;

/**
 * Class Category.
 *
 * @package namespace Modules\Category\Eloquent\Entities;
 */
class Category extends Model implements Transformable
{
    use TransformableTrait;

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
        'parent_id',
    ];


    protected $appends = [
        'value',
        'label',
        'status_label',
        'page_url'
    ];


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

    public function scopeIsActive($query,$type = 'active') {
        return $query->where('status_identifier',$type);
    }

    public function getPageUrlAttribute() {
        return hasRoute('web.category.detail') ?  route('web.category.detail',$this->slug) : '#';
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
