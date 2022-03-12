<?php

namespace App\App\Eloquent\Entities;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\App\Eloquent\Observers\HomePageFooterObserver;

/**
 * Class HomePageFooter.
 *
 * @package namespace App\App\Eloquent\Entities;
 */
class HomePageFooter extends Model implements Transformable
{
    use TransformableTrait, NodeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'href',
        'icon',
        'target',
        'text',
        'title',
        'parent_id',
        'position'
    ];


    protected $appends = [
        'name'
    ];


    public function getNameAttribute() {
        return $this->text;
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

        HomePageFooter::observe(new HomePageFooterObserver());
    }

}
