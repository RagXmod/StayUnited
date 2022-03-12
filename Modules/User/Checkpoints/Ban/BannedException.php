<?php

namespace Modules\User\Checkpoints\Ban;


use Cartalyst\Sentinel\Users\UserInterface;
use RuntimeException;

class BannedException extends RuntimeException
{
    /**
     * The user which caused the exception.
     *
     * @var \Cartalyst\Sentinel\Users\UserInterface
     */
    protected $user;

    /**
     * Returns the user.
     *
     * @return \Cartalyst\Sentinel\Users\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the user associated with Sentinel (does not log in).
     *
     * @param  \Cartalyst\Sentinel\Users\UserInterface
     * @return void
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

}