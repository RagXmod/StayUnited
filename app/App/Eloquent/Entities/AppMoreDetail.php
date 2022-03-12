<?php

namespace App\App\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\App\Eloquent\Observers\AppMoreDetailObserver;

/**
 * Class AppMoreDetail.
 *
 * @package namespace App\App\Eloquent\Entities;
 */
class AppMoreDetail extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id',
        'identifier',
        'title',
        'value'
    ];


    public function app()
    {
        return $this->belongsTo(\App\App\Eloquent\Entities\App::class);
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

        AppMoreDetail::observe(new AppMoreDetailObserver());
    }
}
