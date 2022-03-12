<?php

namespace Modules\User\Facades;


/**
 * Module Api: Modules\User\Facades\UserFacade.php
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

use Cache;
use Exception;
use Illuminate\Support\Facades\Route;
use Modules\User\Eloquent\Entities\User;
use Modules\User\Eloquent\Entities\Throttle;

/**
 * Class UserFacade
 *
 * @package namespace Modules\User\Facades;
 */
class UserFacade
{

    // @var integer 1440
    public $cacheTTL = 1440; //24hours

    // @var mixed Modules\User\Eloquent\Entities\User
    public $userModel;

    // @var mixed Modules\User\Eloquent\Entities\Throttle
    public $throttleModel;


    public function dashboardInfo() {

        return [

            'total_users'               => $this->totalUsers(),
            'total_new_users'           => $this->totalUsersWithinThisMonth(),
            'total_banned_users'        => $this->totalBannedUsers(),
            'total_suspended_users'     => $this->totalSuspendedUsers(),
            'latest_registered_users'   => $this->totalRegisteredUsersForThisMonth(),
            'total_users_groupby_month' => $this->totalRegisteredUsersThisYearByMonth(),

        ];
    }

    public function totalUsers() {

        $model    = $this->getUserModel();
        $cacheKey = $model->cacheKeyArray('total_users');
        return Cache::remember( $cacheKey, $this->cacheTTL, function() use($model) {
            return $model->count();
        });
    }

    public function totalRegisteredUsersThisYearByMonth() {

        $model    = $this->getUserModel();
        $cacheKey = $model->cacheKeyArray('total_registered_users_this_year_by_month');
        return Cache::remember( $cacheKey, $this->cacheTTL, function() use($model) {
            return $model->totalRegisteredUsersThisYearByMonth();
        });
    }


    public function totalUsersWithinThisMonth() {

        $model    = $this->getUserModel();
        $cacheKey = $model->cacheKeyArray('total_users_this_month');
        return Cache::remember( $cacheKey, $this->cacheTTL, function() use($model) {
            return $model->totalUsersThisMonth();
        });
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function totalRegisteredUsersForThisMonth() {

        $model    = $this->getUserModel();

        $cacheKey = $model->cacheKeyArray('total_registered_users_this_month');
        return Cache::remember( $cacheKey, $this->cacheTTL, function() use($model) {
            return $model->latest()->take(5)->get()->map(function($m){
                return [
                    'detail_url'    => Route::has('admin.user.detail') ? route('admin.user.detail', $m->id) : '#',
                    'full_name'     => $m->full_name,
                    'email'         => $m->email,
                    'avatar'        => $m->avatar ?? null,
                    'registered_at' => $m->created_at->diffForHumans(),
                ];
            });
        });
    }



    /**
     * Undocumented function
     *
     * @return void
     */
    public function totalBannedUsers() {

        $model    = $this->getThrottleModel();

        $name = $model->cacheKeyArray('total_banned_users');
        $cacheKey = "{$model->tableNameWithPrefix()}:{$name}";
        return Cache::remember( $cacheKey, $this->cacheTTL, function() use($model) {
            return $model->totalBannedUsers();
        });
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function totalSuspendedUsers() {

        $model    = $this->getThrottleModel();

        $name = $model->cacheKeyArray('total_suspended_users');
        $cacheKey = "{$model->tableNameWithPrefix()}:{$name}";
        return Cache::remember( $cacheKey, $this->cacheTTL, function() use($model) {
            return $model->totalSuspendedUsers();
        });
    }



    /**
     *
     * @param [type] $userModel
     * @return void
     */
    public function setUserModel( $userModel) {
        $this->userModel = $userModel;
        return $this;
    }

    /**
     *
     * @param [type] $userModel
     * @return void
    */
    public function setThrottleModel( $throttleModel) {
        $this->throttleModel = $throttleModel;
        return $this;
    }

    private function getUserModel( ) {
        $model = $this->userModel;
        if ( !$this->userModel )
            $model = app(User::class);
        return $model;

    }

    private function getThrottleModel( ) {
        $model = $this->throttleModel;
        if ( !$this->throttleModel )
            $model = app(Throttle::class);
        return $model;

    }
}
