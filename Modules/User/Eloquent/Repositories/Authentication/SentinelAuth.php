<?php

/**
 * Module User: Modules\User\Repositories\Sentinel\SentinelAuth
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

namespace Modules\User\Eloquent\Repositories\Authentication;

use Exception;
use Modules\User\Contracts\Authentication;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Modules\User\Checkpoints\SuspendedException;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
// use Modules\User\Events\UserHasActivatedAccount;
use Modules\User\Eloquent\Entities\User;

class SentinelAuth implements Authentication
{

    /**
     * Authenticate a user
     * @param  array $credentials
     * @param  bool  $remember    Remember the user
     * @return mixed
     */
    public function login($credentials, $remember = false)
    {



        // $throttleRepository = app(\Cartalyst\Sentinel\Throttling\IlluminateThrottleRepository::class);

        // $holds = $throttleRepository->getUserThresholds();



        // Sentinel::setThrottleRepository(app('sentinel.throttling'));
        // $throttle = Sentinel::getThrottleRepository();

        // $throttle->setGlobalThresholds(['10' => 2]);
        // $throttle->setGlobalInterval(2);
        // $throttle->setIpThresholds(['10' => 2]);
        // $throttle->setIpInterval(2);
        // $throttle->setGlobalInterval(2);
        // $throttle->setUserThresholds(2);
        // $throttle->setUserInterval(2);

        // dd($throttle);
        // app('sentinel.throttling')

        // dd(  Sentinel::authenticate($credentials, $remember)) ;

        try {

            if (Sentinel::authenticate($credentials, $remember)) {
                return false;
            }
            return __('user::auth.failed');

        } catch (NotActivatedException $e) {
            logger()->debug($e);
            return __('user::auth.not_activated');
        } catch (ThrottlingException $e) {
            logger()->debug($e);
            $delay = $e->getDelay();
            return __('user::auth.throttle', ['delay' => $delay]);
        } catch (SuspendedException $e) {
            logger()->debug($e);
            return $e->getMessage();
        }
    }

    /**
     * Register a new user.
     * @param  array $user
     * @return bool
     */
    public function register(array $user)
    {
        return Sentinel::getUserRepository()->create((array) $user);
    }

    /**
     * Register a new user. and auto activate account
     * @param  array $user
     * @return bool
     */
    public function registerAndActivate(array $credentials)
    {
        return Sentinel::registerAndActivate($credentials);
    }


    /**
     * Login from the system using user id.
     * @param int $userId
     * @return bool
     */
    public function loginUsingId($userId)
    {
        $user = app(\Modules\User\Eloquent\Repositories\UserRepositoryEloquent::class)->find($userId);
        return $this->login($user);
    }


    /**
     * Login from the system using user id.
     * @param int $userId
     * @return bool
     */
    public function findById($userId)
    {
        $user = app(\Modules\User\Eloquent\Repositories\UserRepositoryEloquent::class)->find($userId);
        return $user;
    }


    /**
     * Assign a role to the given user.
     * @param  \Modules\User\Repositories\UserRepository $user
     * @param  \Modules\User\Repositories\RoleRepository $role
     * @return mixed
     */
    public function findRoleBySlug($role)
    {
        return Sentinel::findRoleBySlug( $role );
    }

    /**
     * Assign a role to the given user.
     * @param  \Modules\User\Repositories\UserRepository $user
     * @param  \Modules\User\Repositories\RoleRepository $role
     * @return mixed
     */
    public function assignRole($user, $role)
    {
        return $role->users()->attach($user);
    }

    /**
     * Log the user out of the application.
     * @return bool
     */
    public function logout($id)
    {
        $user = Sentinel::findById($id);
        return Sentinel::logout($user);
    }

     /**
     * Update the given used id
     * @param  int    $userId
     * @param  array $credentials
     * @return mixed
     */
    public function update($userId, $credentials)
    {
        $user = Sentinel::findById($userId);
        return Sentinel::update($user, $credentials);
    }


    /**
     * Activate the given used id
     * @param  int    $userId
     * @param  string $code
     * @return mixed
     */
    public function activate($user, $code)
    {

        if ( !$user instanceof User)
            $user = Sentinel::findById($user);

        $success = Activation::complete($user, $code);
        // if ($success) {
        //     event(new UserHasActivatedAccount($user));
        // }

        return $success;
    }

     /**
     * Activate the given used id
     * @param  \Modules\User\Repositories\UserRepository $user
     * @return mixed
     */
    public function isActivated($user)
    {
        return Activation::completed($user) ? true : false;
    }


    /**
     * Activate the given used id
     * @param  \Modules\User\Repositories\UserRepository $user
     * @return mixed
     */
    public function removeActivation($user)
    {
        return Activation::remove($user);
    }

    /**
     * Create Activation the given user
     * @param  \Modules\User\Repositories\UserRepository $user
     * @return mixed
     */
    public function reActivate($user)
    {
        if ( $this->isActivated( $user) )
            return false;

        $activationCode = $this->createActivation($user);
        return $this->activate( $user, $activationCode );

    }


    /**
     * Create an activation code for the given user
     * @param  \Modules\User\Repositories\UserRepository $user
     * @return mixed
     */
    public function createActivation($user)
    {
        return Activation::create($user)->code;
    }

    /**
     * Create a reminders code for the given user
     * @param  \Modules\User\Repositories\UserRepository $user
     * @return mixed
     */
    public function createReminderCode($user)
    {
        $reminder = Reminder::exists($user) ?: Reminder::create($user);

        return $reminder->code;
    }

    /**
     * Completes the reset password process
     * @param $user
     * @param  string $code
     * @param  string $password
     * @return bool
     */
    public function completeResetPassword($user, $code, $password)
    {
        return Reminder::complete($user, $code, $password);
    }

    /**
     * Determines if the current user has access to given permission
     * @param $permission
     * @return bool
     */
    public function hasAccess($permission)
    {
        if (! Sentinel::check()) {
            return false;
        }

        return Sentinel::hasAccess($permission);
    }

    /**
     * Check if the user is logged in
     * @return bool
     */
    public function check()
    {
        $user = Sentinel::check();

        if ($user) {
            return true;
        }

        return false;
    }

    /**
     * Get the currently logged in user
     * @return \Modules\User\Entities\UserInterface
     */
    public function user()
    {
        return Sentinel::check();
    }

    /**
     * Get the ID for the currently authenticated user
     * @return int
     */
    public function id()
    {
        $user = $this->user();

        if ($user === null) {
            return 0;
        }

        return $user->id;
    }


    /**
     * Validate Credentials
     *
     * @param [type] $userId
     * @param [type] $credentials
     * @return void
     */
    public function validateCredentials($userId, $credentials) {

        return Sentinel::validateCredentials($userId, $credentials);
    }


    /**
     * Find User based on credentials
     *
     * @param [type] $credentials
     * @return void
     */
    public function findByCredentials($credentials) {
        return Sentinel::findByCredentials($credentials);
    }



    /**
     * Remove specific checkpoints..
     *
     * @param [type] $checkpoint
     * @return void
     */
    public function removeCheckpoint($checkpoint) {
        return Sentinel::removeCheckpoint( $checkpoint );
    }




}