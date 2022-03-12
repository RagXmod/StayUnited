<?php

namespace Modules\Page\Eloquent\Entities;

use Modules\Core\Traits\EntityTrait;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Modules\Page\Eloquent\Observers\PageObserver;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Page.
 *
 * @package namespace Modules\Page\Eloquent\Entities;
 */
class Page extends Model implements Transformable
{
    use TransformableTrait, EntityTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'user_id',
    	'identifier',
    	'status_identifier',
    	'page_id',
    	'slug',
    	'title',
    	'content',
    	'icon',
        'seo_title',
        'seo_keyword',
        'seo_description',
        'position',
    	'is_enabled',
    	'is_demo',
    ];

    public $appends = [
        'status_label',
        'page_url'
    ];

    public function getStatusLabelAttribute() {
        return pageStatusArr( $this->status_identifier);
    }

    public function getSeoKeywordAttribute( $value ) {

        return commaStringToArrayKeywords($value);
    }

    public function getPageUrlAttribute() {
        return hasRoute('web.page.detail') ?  route('web.page.detail',$this->slug) : '#';
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

        Page::observe(new PageObserver());
    }
}
