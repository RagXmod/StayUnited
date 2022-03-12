<?php

namespace Modules\User\Eloquent\Entities;

// use Illuminate\Database\Eloquent\Model;
use Cache;
use Illuminate\Auth\Authenticatable;
use Modules\Core\Traits\EntityTrait;
use Prettus\Repository\Contracts\Transformable;

use Prettus\Repository\Traits\TransformableTrait;

use Cartalyst\Sentinel\Throttling\EloquentThrottle;
use Modules\User\Eloquent\Observers\ThrottleObserver;

/**
 * Class User.
 *
 * @package namespace Modules\User\Eloquent\Entities;
 */
class Throttle extends EloquentThrottle implements Transformable
{
    use TransformableTrait, EntityTrait;

    protected $table = 'throttle';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'ip',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(\Modules\User\Eloquent\Entities\User::class);
    }

    public function scopeTotalSuspendedUsers($query)
    {
        return $query->where('suspended',1)->count();
    }

    public function scopeTotalBannedUsers($query)
    {
        return $query->where('banned',1)->count();
    }


    public function cacheKeyArray($key = null) {
        $data = [
            'total_banned_users'    => 'total_banned_users',
            'total_suspended_users' => 'total_suspended_users'
        ];

        if($key)
            return isset($data[$key]) ? $data[$key] : '';

        return $data;
    }

    protected static function boot() {
        parent::boot();

        Throttle::observe(new ThrottleObserver());
    }


}