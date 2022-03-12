<?php

namespace App\App\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class AppFeaturedPostable.
 *
 * @package namespace App\App\Eloquent\Entities;
 */
class AppFeaturedPostable extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'app_featured_post_id',
        'app_featured_postable_id',
        'app_featured_postable_type',
    ];

}
