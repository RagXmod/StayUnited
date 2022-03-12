<?php

/**
 * Module Core: App\Http\Controllers\Web\User\UserController
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

namespace App\Http\Controllers\Web\User;

use Reminder;
use Exception;
use Modules\User\Http\Controllers\AuthController;

class UserController extends AuthController
{

    public $routes = [
        'home'            => 'web.home.index',
        'forgot_password' => 'frontend.forgot.password',
        'reset_password'  => 'web.user.reset-password',
    ];

    public function getLogin()
    {
        $return_url = '';
        if ( request()->has('return-url') )
            $return_url .= request()->input('return-url');

        $data = [
            'authenticate_url'  => route('web.user.authenticate', ['return-url' => strip_tags(trim($return_url))])
        ];
        return view('web.user.login', $data);
    }

     /**
     * getNewAccount
     *
     * @return view
     */
    public function getNewAccount()
    {
        return view('web.user.new-account')->with(['url' => route('web.user.post-new-account')]);
    }

     /**
     * Logout current user.
     *
     * @return void
     */
    public function getForgotPassword()
    {
        return view('web.user.forgot-password')->with(['url' => route('web.user.post-forgot-password')]);
    }

    /**
    *
    * getResetPassword()
    *
    * @return template
    * @access  public
    **/
    public function getResetPassword($userHashId,$reminderCode)
    {

        try {

            $hashId = hasher($userHashId,  true);
            if ( !$hashId )
                throw new Exception('Wrong user hash key, please check the url carefully.');

            $user = $this->auth->findById($hashId);

            $isReminderCodeExist = Reminder::exists($user);
            if(!$isReminderCodeExist)
                throw new Exception("Reset code is not exists, please retry.");

            return view('web.user.reset-password')->with(['url' => route('web.user.post-reset-password'),
                                                        'code'    => $reminderCode,
                                                        'hash_code' => $userHashId
                                                    ]);

        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }

}
