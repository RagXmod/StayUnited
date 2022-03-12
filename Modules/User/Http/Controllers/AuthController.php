<?php

namespace Modules\User\Http\Controllers;

use Mail;
use Reminder;
use Sentinel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Traits\ResponseTrait;
use Modules\User\Http\Requests\LoginRequest;
use Modules\Core\Http\Controllers\BaseController;
use Modules\User\Http\Requests\NewAccountRequest;
use Modules\User\Http\Requests\ResetPasswordRequest;
use Modules\User\Http\Requests\ForgotPasswordRequest;

class AuthController extends BaseController
{
    use ResponseTrait;

    public $routes = [
        'home'            => '',
        'forgot_password' => '',
        'reset_password'  => '',
    ];

    /**
     * Authenticate user based on credentials sent.
     *
     * @param LoginRequest $request
     * @return url
     */
    public function postAuthenticate(LoginRequest $request)
    {
        try {

            $credentials = [
                'login'     => $request->login,
                'password'  => $request->password
            ];

            $remember = (bool) $request->get('remember_me', false);
            $validate = $this->auth->login($credentials, $remember);

            // // for ajax responses
            if( isAjax() ) {

                if ($validate) {
                    return $this->failed($validate);
                } else {
                    $user = $this->auth->user();
                    return $this->success([
                            'user' => $user
                        ]);
                }
            } else {

                if ($validate) {
                    return $this->redirectWithError($validate);
                }

                $isAdmin = $this->auth->hasAccess( config('user.auth.has_admin_permission_to_login') );
                $redirectTo = $this->redirecto( $request );

                $redirectTo = ($isAdmin) ?  $redirectTo : $this->routes['home'];

                if( hasRoute( $redirectTo ) ) {
                    return redirect()->intended(route($redirectTo))
                        ->with(['success-login' => __('user::auth.success_login') ]);
                }

                return redirect($redirectTo)
                        ->with(['success-login' => __('user::auth.success_login') ]);
            }

        } catch (Exception $e) {
            logger()->debug($e);
            if(isAjax()) {
                return $this->failed($e->getMessage());
            }
            return $this->redirectWithError($e->getMessage());
        }
    }

    /**
     * Logout current user.
     *
     * @return void
     */
    public function logout()
    {

        $user = $this->auth->user();
        if ( $user ) {
            $this->auth->logout($user->id);
        }
        Auth::logout();
        return redirect( route($this->routes['home']) )->with(['success-login' => __('user::auth.success_logout') ]);
    }


    /**
     * Undocumented function
     *
     * @param NewAccountRequest $request
     * @return void
     */
    public function postNewAccount(NewAccountRequest $request)
    {
        try {

            DB::beginTransaction();
                $credentials = $request->only(['email', 'first_name','last_name','username','password']);

              
                // add condition here.. for auto activate account or not
                $newUser     = $this->auth->registerAndActivate($credentials);
                
                // $newUser     = $this->auth->register($credentials);

                $regularRole  = Sentinel::findRoleBySlug('regular');

                $this->auth->assignRole($newUser, $regularRole);
                $this->auth->loginUsingId($newUser->id);

            DB::commit();
            return redirect()->intended( route( $this->routes['home']) );

        } catch (Exception $e) {
            dd($e);
            logger()->debug($e);
            DB::rollback();
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }

    /**
    *
    * postForgotPassword()
    *
    * @return template
    * @access  public
    **/
    public function postForgotPassword(ForgotPasswordRequest $request)
    {

        try {

            DB::beginTransaction();

                $credentials = [
                    'login' => $request->get('email')
                ];
                $user = $this->auth->findByCredentials( $credentials);
                if (!$user)
                    throw new Exception("Email doesnt exists in our system");

                $this->sendCustomerReminderEmail($user);
                session()->flash('success', 'We sent a reset password link in your email ('.$user->email.')');


                DB::commit();

            return redirect()->route($this->routes['forgot_password']);

        } catch (Exception $e) {

            DB::rollback();
            logger()->debug($e);
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }

    /**
    *
    * postResetPassword()
    *
    * @return template
    * @access  public
    **/
    public function postResetPassword(ResetPasswordRequest $request)
    {

        try {

            $params        = $request->all();
            $userId        = hasher($params['hash_code'], true);
            $reminder_code = $params['reminder_code'];
            $password      = $params['password'];

            $user = $this->auth->findById($userId);
            if ( !$user )
                throw new Exception('User does not exists.');

            $reminderExists = Reminder::exists($user);
            if(!$reminderExists)
                throw new Exception("Reset code does not exists", 1);

            if ($reminder = Reminder::complete($user, $reminder_code, $password))
            {
                $type    = 'message';
                $message = 'Your password was successfully reset. Please login your account with your new password.';
            }
            else
            {
                $type    = 'message';
                $message = 'Reset password failed, please try again and forgot your password.';
            }
            session()->flash($type, $message);
            return redirect()->route( $this->routes['home'] );

        } catch (Exception $e) {
            logger()->debug($e);
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }


    /**
     * Undocumented function
     *
     * @param [type] $user
     * @return void
     */
    public function sendCustomerReminderEmail($user) {

        $reminder     = Reminder::create($user);
        $userInfo     = $user->toArray();

        $userInfo['reminder_code'] = $reminder->code;
        $userInfo['reminder_link'] = route($this->routes['reset_password'],[ hasher($userInfo['id']), $reminder->code ]);

        Mail::send('emails.forgot-password', ['data' => $userInfo], function ($message) use ($userInfo) {

            $email = 'buymyscriptcodecanyon@mailinator.com';
            if(dcmConfig('site_email') != '')
                $email = dcmConfig('site_email');

            $userEmail = $userInfo['email'];

            $message->from($email, dcmConfig('site_title'))
                    ->to($userEmail, $userInfo['first_name'].' '.$userInfo['last_name'])
                    ->subject('Reset Account Password from '.dcmConfig('site_title'));
        });
    }

    public function redirectWithError( $message = null) {
        return redirect()->back()->withInput()->withError(['message' => $message]);
    }


    /**
     * Redirect to url after login.
     *
     * @param [type] $request
     * @return void
     */
    public function redirecto( $request ) {
        $redirectTo = '';
        if ( $request->has('return-url') )
            $redirectTo .= $request->input('return-url');
        else
            $redirectTo = config('user.redirect_route_after_login');

        return $redirectTo;
    }


}