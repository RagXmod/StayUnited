<?php

namespace Modules\User\Eloquent\Entities;

// use Illuminate\Database\Eloquent\Model;
use Cache;
use Illuminate\Auth\Authenticatable;
use Cartalyst\Sentinel\Users\EloquentUser;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Prettus\Repository\Contracts\Transformable;

use Prettus\Repository\Traits\TransformableTrait;

use Modules\User\Eloquent\Traits\UploadEloquentTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Modules\Core\Traits\EntityTrait;
use DB;

/**
 * Class User.
 *
 * @package namespace Modules\User\Eloquent\Entities;
 */
class User extends EloquentUser implements Transformable, AuthenticatableContract
{
    use TransformableTrait, Authenticatable, UploadEloquentTrait, EntityTrait;

    /**
     * {@inheritDoc}
     */
    protected $loginNames = ['email', 'username'];

    protected $appends = ['full_name', 'hash_id','avatar', 'status_identifier'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'username',
        'password',
        'permissions',
        'first_name',
        'last_name',
        'is_test_mode_account',
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function scopeTotalUsersThisMonth($query)
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth   = now()->endOfMonth();

        return $query->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
    }


    public function scopeTotalRegisteredUsersThisYearByMonth($query, $startFromYear = null)
    {
        if ( !$startFromYear )
            $startFromYear = now()->year;

        $rawSQL      = "count(*) as total_users, DATE_FORMAT(created_at,'%c') as month";
        $collections = $this->select(DB::raw($rawSQL))
                        ->where(DB::raw('YEAR(created_at) '), $startFromYear)
                        ->groupBy('month')
                        ->get();

        $months = range(01, 12);
        $_collect = [];
        foreach( $months  as $month ) {
            $_collect[$month] = 0;
            foreach($collections as $collect) {
                if (  $collect->month == $month)
                    $_collect[$month] = $collect->total_users;
            }
        }
        return $_collect;
    }

    public function unHashUserId($userHashId )
    {
        $userId = hasher($userHashId, true);
        return $userId;
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getHashIdAttribute()
    {
        return hasher($this->id);
    }


    public function getStatusIdentifierAttribute( )
    {
        $defaultStatus = config('user.status.active');
        if ( !Activation::completed($this) )
            $defaultStatus = config('user.status.unconfirmed');

        $banModel = app(\Modules\User\Checkpoints\Ban\BanRepository::class);
        $isBanned =  $banModel->isBanned($this);
        if ($isBanned)
            $defaultStatus = config('user.status.banned');

        $suspendModel = app(\Modules\User\Checkpoints\Suspension\SuspensionRepository::class);
        $isSuspended =  $suspendModel->isSuspended($this);
        if ($isSuspended)
            $defaultStatus = config('user.status.suspend');

        $suspendModel = app(\Modules\User\Checkpoints\Suspension\SuspensionRepository::class);
        $isSuspended =  $suspendModel->isSuspended($this);
        if ($isSuspended)
            $defaultStatus = config('user.status.suspend');

        // dd($defaultStatus);
        return $defaultStatus;
    }

    public function userActivities()
    {
        return $this->hasMany(\Modules\User\Eloquent\Entities\UserActiviy::class);
    }

    public function userAvatar()
    {
        return $this->hasOne(\Modules\User\Eloquent\Entities\UserUpload::class)->typeAvatar();
    }

    public function userDetail()
    {
        return $this->hasOne(\Modules\User\Eloquent\Entities\UserDetail::class);
    }

    public function userThrottle()
    {
        return $this->hasOne(\Modules\User\Eloquent\Entities\Throttle::class);
    }


    public function getAvatarAttribute()
    {

        $imageUrl = asset('img/profile.png');
        $hasAvatarUpload = $this->userAvatar()->first();
        if ( $hasAvatarUpload) {
            $imageUrl = $hasAvatarUpload->avatar_url;
        }
        return $imageUrl;
    }

    public function getMyGravatar($email = null)
    {
        if( !$email ) {
            $email = $this->email;
        }
        $userUpload = app(\Modules\User\Eloquent\Entities\UserUpload::class);
        return $userUpload->myGravatar( $email );
    }

    public function compareHashId( $userHashId )
    {
        $userId = hasher($userHashId, true);
        return (bool) ($this->id === $userId );
    }


    public function cacheKeyArray($key = null) {
        $data = [
            'total_registered_users_this_month'         => 'total_registered_users_this_month',
            'total_users_this_month'                    => 'total_users_this_month',
            'total_registered_users_this_year_by_month' => 'total_registered_users_this_year_by_month',
            'total_users'                               => 'total_users',
            'user_roles_cache_keys'                     => config('user.user_roles_cache_keys'),
            'user_status_cache_key'                     => config('user.user_status_cache_key'),
            'tbl_name'                                  => $this->getTable()
        ];

        if($key)
            return isset($data[$key]) ? $data[$key] : '';

        return $data;
    }

}
