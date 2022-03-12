<?php

namespace Modules\User\Checkpoints\Suspension;

use Cartalyst\Sentinel\Users\UserInterface;

interface SuspensionInterface
{
    public function create(UserInterface $user);

    public function exists(UserInterface $user);

    public function suspend(UserInterface $user);

    public function isSuspended(UserInterface $user);

    public function remove(UserInterface $user);

    public function removeExpired();
}
