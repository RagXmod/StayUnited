<?php

namespace Modules\Configuration\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Modules\Configuration\Eloquent\Observers\ConfigurationObserver;
use Modules\Core\Traits\EntityTrait;

/**
 * Class Configuration.
 *
 * @package namespace Modules\Configuration\Eloquent\Entities;
 */
class Configuration extends Model implements Transformable
{
    use TransformableTrait, EntityTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group',
        'identifier',
        'value',
        'description',
    ];

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

        Configuration::observe(new ConfigurationObserver());
    }
}
