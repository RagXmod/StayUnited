<?php

namespace Modules\User\Guards;

use Cartalyst\Sentinel\Sentinel;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\UserProvider;


class SentinelGuard implements StatefulGuard
{
    use GuardHelpers;

    /**
     * @var \Cartalyst\Sentinel\Sentinel
     */
    private $sentinel;

    /**
     * SentinelGuard constructor.
     *
     * @param \Cartalyst\Sentinel\Sentinel $sentinel
     * @param \Illuminate\Contracts\Auth\UserProvider $provider
     */
    public function __construct(Sentinel $sentinel, UserProvider $provider)
    {
        $this->sentinel = $sentinel;
        $this->provider = $provider;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return (bool) $this->sentinel->check();
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return $this->sentinel->guest();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Cartalyst\Sentinel\Users\UserInterface
     */
    public function user()
    {
        return $this->sentinel->getUser();
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public function id()
    {
        if ($user = $this->sentinel->check()) {
            return $user->getUserId();
        }

        return null;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return $this->sentinel
            ->getUserRepository()
            ->validForCreation($credentials);
    }

    /**
     * Set the current user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @return void
     */
    public function setUser(Authenticatable $user)
    {
        $this->sentinel->login($user);
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array $credentials
     * @param  bool $remember
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        return $this->sentinel->authenticate(
            $credentials, $remember
        );
    }

    /**
     * Log a user into the application without sessions or cookies.
     *
     * @param  array $credentials
     * @return bool
     */
    public function once(array $credentials = [])
    {
        throw new \LogicException('Not implemented.');
    }

    /**
     * Log a user into the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  bool $remember
     * @return void
     */
    public function login(Authenticatable $user, $remember = false)
    {
        $this->sentinel->login($user, $remember);
    }

    /**
     * Log the given user ID into the application.
     *
     * @param  mixed $id
     * @param  bool $remember
     * @return bool|\Cartalyst\Sentinel\Users\UserInterface
     */
    public function loginUsingId($id, $remember = false)
    {
        $user = $this->sentinel
            ->getUserRepository()
            ->findById($id);

        return $this->sentinel->login($user, $remember);
    }

    /**
     * Log the given user ID into the application without sessions or cookies.
     *
     * @param  mixed $id
     * @return bool
     */
    public function onceUsingId($id)
    {
        throw new \LogicException('Not implemented.');
    }

    /**
     * Determine if the user was authenticated via "remember me" cookie.
     *
     * @return bool
     */
    public function viaRemember()
    {
        // ???
        return (bool) $this->sentinel->check();
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        $this->sentinel->logout();
    }

    /**
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function authenticate()
    {
        if ( ! $this->check()) {
            throw new AuthenticationException;
        }
    }
}