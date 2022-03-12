<?php

namespace Modules\User\Checkpoints\Ban;


use Cartalyst\Sentinel\Users\UserInterface;
use Modules\User\Checkpoints\Ban\BanInterface;

use Cartalyst\Support\Traits\RepositoryTrait;
use Carbon\Carbon;

class BanRepository implements BanInterface
{

    use RepositoryTrait;

    protected $model = 'Modules\User\Checkpoints\Ban\EloquentBan';

    public function create(UserInterface $user)
    {
        $ban = $this->createModel();

        $ban->user_id = $user->getUserId();

        $ban->save();

        return $ban;
    }

    public function exists(UserInterface $user)
    {
        $ban = $this
            ->createModel()
            ->newQuery()
            ->where('user_id', $user->getUserId());

        return $ban->first() ?: false;
    }

    public function ban(UserInterface $user)
    {
        $exists = $this->exists($user);

        if ($exists) {
            $ban = $this
                ->createModel()
                ->newQuery()
                ->where('user_id', $user->getUserId())
                ->where('banned', false)
                ->first();

            if ($ban === null) {
                return false;
            }

            $ban->fill([
                'banned'    => true,
                'banned_at' => Carbon::now(),
            ]);

            $ban->save();
            return true;

        } else {
            $ban = $this
                ->createModel();

            $ban->fill([
                'user_id'   => $user->getUserId(),
                'type'      => 'user',
                'banned'    => true,
                'banned_at' => Carbon::now(),
            ]);

            $ban->save();
            return true;
        }
    }

    public function unban(UserInterface $user)
    {
        if ($this->isBanned($user))
        {
            $ban = $this
                ->createModel()
                ->newQuery()
                ->where('user_id', $user->getUserId())
                ->where('banned', true)
                ->first();

            if ($ban === null) {
                return false;
            }

            $ban->fill([
                'banned'    => false,
                'banned_at' => null,
            ]);

            $ban->save();
            return true;
        }
    }

    public function isBanned(UserInterface $user)
    {
        $ban = $this
            ->createModel()
            ->newQuery()
            ->where('user_id', $user->getUserId())
            ->where('banned', true)
            ->first();

        return $ban ?: false;
    }

    public function remove(UserInterface $user)
    {
        $ban = $this->ban($user);

        if ($ban === false) {
            return false;
        }

        return $ban->delete();
    }

}
