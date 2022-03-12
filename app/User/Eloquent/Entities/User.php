<?php

namespace App\User\Eloquent\Entities;

use Laravelista\Comments\Commenter;
use Modules\User\Eloquent\Entities\User as UserModule;

/**
 * Class User.
 *
 * @package namespace App\User\Eloquent\Entities;
 */
class User extends UserModule
{
    use Commenter;

}
