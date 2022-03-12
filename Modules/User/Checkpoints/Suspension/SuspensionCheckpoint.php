<?php

namespace Modules\User\Checkpoints\Suspension;


use Cartalyst\Sentinel\Users\UserInterface;
use Modules\User\Checkpoints\Suspension\SuspendedException;
use Modules\User\Checkpoints\Suspension\SuspensionRepository;
use Cartalyst\Sentinel\Checkpoints\CheckpointInterface;
use Exception;
class SuspensionCheckpoint implements CheckpointInterface
{

    public function __construct()
    {
        $this->suspensions = app(SuspensionRepository::class);
    }

    /**
     * Checkpoint after a user is logged in. Return false to deny persistence.
     *
     * @param  \Cartalyst\Sentinel\Users\UserInterface  $user
     * @return bool
     */
    public function login(UserInterface $user) {
        return $this->checkSuspension($user);
    }

    /**
     * Checkpoint for when a user is currently stored in the session.
     *
     * @param  \Cartalyst\Sentinel\Users\UserInterface  $user
     * @return bool
     */
    public function check(UserInterface $user) {
        return $this->checkSuspension($user);
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

        if ( $user)
            return $this->checkSuspension($user);

        // throw new Exception('Failed to checked user.');
    }

    protected function checkSuspension(UserInterface $user)
    {

        $suspended = $this->suspensions->isSuspended($user);

        if ($suspended) {
            $exception = new SuspendedException('You have been suspended for ' . $this->suspensions->getSuspensionTime() .' minutes. Remaining time ' . $this->suspensions->getRemainingSuspensionTime($user) . ' minute(s).');
            $exception->setUser($user);

            throw $exception;
        }
    }
}
