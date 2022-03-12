<?php

namespace Modules\User\Checkpoints\Ban;

use Cartalyst\Sentinel\Users\UserInterface;

interface BanInterface
{
    public function create(UserInterface $user);

    public function exists(UserInterface $user);

    public function ban(UserInterface $user);

    public function isBanned(UserInterface $user);

    public function remove(UserInterface $user);

}
