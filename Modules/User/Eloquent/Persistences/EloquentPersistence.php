<?php

namespace Modules\User\Eloquent\Persistences;

use Cartalyst\Sentinel\Persistences\EloquentPersistence as CartalystEloquentPersistence;

class EloquentPersistence extends CartalystEloquentPersistence
{
    /**
     * The users model name.
     *
     * @var string
     */
    protected static $usersModel = 'Modules\User\Eloquent\Entities\User';

}