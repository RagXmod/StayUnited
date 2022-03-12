<?php

namespace Modules\User\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class UserActivity.
 *
 * @package namespace Modules\User\Eloquent\Entities;
 */
class UserActivity extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'description',
        'ip_address',
        'user_agent'
    ];


    public function user()
    {
        return $this->belongsTo(\Modules\User\Eloquent\Entities\User::class);
    }
}
