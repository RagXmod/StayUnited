<?php

namespace Modules\User\Checkpoints\Ban;

use Illuminate\Database\Eloquent\Model;

class EloquentBan extends Model
{
    protected $table = 'throttle';

    protected $fillable = [
        'type',
        'user_id',
        'banned',
        'banned_at'
    ];

    public function getisBannedAttribute($isBanned)
    {
        return (bool) $isBanned;
    }

    public function setisBannedAttribute($isBanned)
    {
        $this->attributes['banned'] = (bool) $isBanned;
    }
}