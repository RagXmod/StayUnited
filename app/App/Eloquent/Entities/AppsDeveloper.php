<?php

namespace App\App\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\App\Eloquent\Observers\AppsDeveloperObserver;

/**
 * Class AppsDeveloper.
 *
 * @package namespace App\App\Eloquent\Entities;
 */
class AppsDeveloper extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id',
        'app_developer_id'
    ];


    public function apps()
    {
        return $this->hasMany(\App\App\Eloquent\Entities\AppsDeveloper::class, 'app_developer_id');
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

        AppsDeveloper::observe(new AppsDeveloperObserver());
    }

}
