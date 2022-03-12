<?php

/**
 * Module Core: App\Http\Controllers\Admin\User\UserController
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

namespace App\Http\Controllers\Admin\User;

use Reminder;
use Exception;
use Modules\User\Http\Controllers\AuthController;

class UserController extends AuthController
{

    public $routes = [
        'home' => 'admin.dashboard.index'
    ];

    public function getLogin()
    {
        $return_url = '';
        if ( request()->has('return-url') )
            $return_url .= request()->input('return-url');

        $data = [
            'return_url'  => $return_url
        ];
        return view('admin.user.login', $data);
    }

}
