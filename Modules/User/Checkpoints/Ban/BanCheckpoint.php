<?php

namespace Modules\User\Checkpoints\Ban;


use Cartalyst\Sentinel\Users\UserInterface;
use Modules\User\Checkpoints\Ban\BannedException;
use Modules\User\Checkpoints\Ban\BanRepository;
use Cartalyst\Sentinel\Checkpoints\CheckpointInterface;
use Exception;

class BanCheckpoint implements CheckpointInterface
{

    public function __construct()
    {
        $this->ban = app(BanRepository::class);
    }

    /**
     * Checkpoint after a user is logged in. Return false to deny persistence.
     *
     * @param  \Cartalyst\Sentinel\Users\UserInterface  $user
     * @return bool
     */
    public function login(UserInterface $user) {
        return $this->checkBan($user);
    }

    /**
     * Checkpoint for when a user is currently stored in the session.
     *
     * @param  \Cartalyst\Sentinel\Users\UserInterface  $user
     * @return bool
     */
    public function check(UserInterface $user) {
        return $this->checkBan($user);
    }

    /**
     * Checkpoint for when a failed login attempt is logged. User is not always
     * passed and the result of the method will not affect anything, as the
     * login failed.
     *
     * @param  \Cartalyst\Sentinel\Users\UserInterface  $user
     * @return void
     */
    public function fail(UserInterface $user = null) {
        if ( $user )
            return $this->checkBan($user);

        // throw new Exception('Failed to checked user.');
    }

    protected function checkBan(UserInterface $user)
    {
        $isBanned = $this->ban->isBanned($user);

        if ($isBanned) {
            $exception = new BannedException('You\'re temporary banned from our system because you may have violated our terms of condition. Feel free to contact us anytime.');

            $exception->setUser($user);

            throw $exception;
        }

    }
}
