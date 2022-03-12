<?php

namespace Modules\User\Checkpoints\Suspension;


use Cartalyst\Sentinel\Users\UserInterface;
use Modules\User\Checkpoints\Suspension\SuspensionInterface;

use Cartalyst\Support\Traits\RepositoryTrait;
use DateTime;

class SuspensionRepository implements SuspensionInterface
{

    use RepositoryTrait;

    protected $model = 'Modules\User\Checkpoints\Suspension\EloquentSuspension';

    protected $expires = 259200;

    protected static $suspensionTime = 15;

    public function __construct($model = null, $expires = null)
    {
        if (isset($model)) {
            $this->model = $model;
        }

        if (isset($expires)) {
            $this->expires = $expires;
        }
    }

    public function all()
    {
        $users = User::all();

        foreach ($users as $user) {
            if ($user->isActivated()) {
                $user->status = "Active";
            } else {
                $user->status = "Not Active";
            }

            $throttle = $this->throttleProvider->findByUserId($user->id);

            if ($throttle->isSuspended()) {
                // User is Suspended
                $user->status = "Suspended";
            }

            if ($throttle->isBanned()) {
                // User is Banned
                $user->status = "Banned";
            }
        }

        return $users;
    }

    public function create(UserInterface $user)
    {
        $suspension = $this->createModel();

        $suspension->user_id = $user->getUserId();

        $suspension->save();

        return $suspension;
    }

    public function exists(UserInterface $user)
    {
        $suspension = $this
            ->createModel()
            ->newQuery()
            ->where('user_id', $user->getUserId());

        return $suspension->first() ?: false;
    }

    public function suspend(UserInterface $user)
    {
        $exists = $this->exists($user);

        if ($exists) {
            $suspend = $this
                ->createModel()
                ->newQuery()
                ->where('user_id', $user->getUserId())
                ->where('suspended', false)
                ->first();

            if ($suspend === null) {
                return false;
            }

            $suspend->fill([
                'suspended'    => true,
                'suspended_at' => now(),
            ]);

            $suspend->save();
            return true;

        } else {
            $suspend = $this
                ->createModel();

            $suspend->fill([
                'user_id'      => $user->getUserId(),
                'type'         => 'user',
                'suspended'    => true,
                'suspended_at' => now(),
            ]);

            $suspend->save();

            return true;
        }
    }

    public function unsuspend(UserInterface $user)
    {
        $suspend = $this
            ->createModel()
            ->newQuery()
            ->where('user_id', $user->getUserId())
            ->where('suspended', true)
            ->whereNotNull('suspended_at')
            ->first();

        if ($suspend === null) {
            return false;
        }

        $suspend->fill([
            'suspended'    => false,
            'suspended_at' => null,
        ]);

        $suspend->save();

        return true;
    }

    public function suspended(UserInterface $user)
    {
        $suspended = $this
            ->createModel()
            ->newQuery()
            ->where('user_id', $user->getUserId())
            ->where('suspended', true)
            ->first();

        if ($suspended !== null)
        {
            $this->removeSuspensionIfAllowed($user);
            return (bool) $this->suspended($user);
        } else {
            return false;
        }
    }

    public function isSuspended(UserInterface $user)
    {
        $isSuspended = $this
            ->createModel()
            ->newQuery()
            ->where('user_id', $user->getUserId())
            ->where('suspended', true)
            ->first();

        if ($isSuspended !== null) {

            $remove = $this->removeSuspensionIfAllowed($user);

            return $remove;
        } else {
            return false;
        }
    }

    public function remove(UserInterface $user)
    {

        $suspension = $this->suspended($user);

        if ($suspension === false) {
            return false;
        }

        return $suspension->delete();
    }

    public function removeExpired()
    {
        $expires = $this->expires();

        return $this
            ->createModel()
            ->newQuery()
            ->where('suspended', false)
            ->where('created_at', '<', $expires)
            ->delete();
    }

    protected function expires()
    {
        return now()->subSeconds($this->expires);
    }

    protected function generateSuspensionCode()
    {
        return str_random(32);
    }

    // public function getDates()
    // {
    //     return array_merge(parent::getDates(), array('suspended_at', 'banned_at'));
    // }

    // public function toArray()
    // {
    //     $result = parent::toArray();

    //     if (isset($result['suspended']))
    //     {
    //         $result['suspended'] = $this->getSuspendedAttribute($result['suspended']);
    //     }
    //     if (isset($result['banned']))
    //     {
    //         $result['banned'] = $this->getBannedAttribute($result['banned']);
    //     }
    //     if (isset($result['suspended_at']) and $result['suspended_at'] instanceof DateTime)
    //     {
    //         $result['suspended_at'] = $result['suspended_at']->format('Y-m-d H:i:s');
    //     }

    //     return $result;
    // }

    public static function setSuspensionTime($minutes)
    {
        static::$suspensionTime = (int) $minutes;
    }

    public static function getSuspensionTime()
    {
        return static::$suspensionTime;
    }

    public function getSuspendedTime($user)
    {
            $suspendedAt = $this
                ->createModel()
                ->newQuery()
                ->where('user_id', $user->getUserId())
                ->where('suspended', true)
                ->whereNotNull('suspended_at')
                ->first();

            if ($suspendedAt === null) {
                return false;
            }

            return $suspendedAt->suspended_at;
    }

    public function removeSuspensionIfAllowed($user)
    {
        $flag = false;

        $suspensionTime = $this->getSuspensionTime();
        $suspendedAt = new DateTime($this->getSuspendedTime($user));
        $unsuspendAt = $suspendedAt->modify("+{$suspensionTime} minutes");
        $now = new DateTime();
        if ($unsuspendAt <= $now)
        {
            $this->unsuspend($user);

            unset($suspended);
            unset($unsuspendAt);
            unset($now);

            return false;
        } else {
            unset($suspended);
            unset($unsuspendAt);
            unset($now);

            return true;
        }
    }

    public function getRemainingSuspensionTime($user)
    {
        $suspensionTime = $this->getSuspensionTime();
        $suspendedAt = new DateTime($this->getSuspendedTime($user));
        $unsuspendAt = $suspendedAt->modify("+{$suspensionTime} minutes");
        $now = new DateTime();
        $timeLeft = $now->diff($unsuspendAt);

        $minutesLeft = ($timeLeft->s != 0 ?
                    ($timeLeft->days * 24 * 60) + ($timeLeft->h * 60) + ($timeLeft->i) + 1 :
                    ($timeLeft->days * 24 * 60) + ($timeLeft->h * 60) + ($timeLeft->i));
        return $minutesLeft;
    }


}
