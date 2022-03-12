<?php

namespace Modules\User\Eloquent\Repositories;

use Cache;
use Storage;
use Modules\User\Eloquent\Entities\User;
use Modules\Core\Traits\UploadTrait;
use Cartalyst\Sentinel\Roles\EloquentRole;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Modules\User\Eloquent\Observers\UserObserver;
use Modules\User\Eloquent\Interfaces\UserRepository;
use Modules\User\Eloquent\Repositories\UserUploadRepositoryEloquent;

use Sentinel;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace Modules\User\Eloquent\Repositories;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{

    use UploadTrait;

    const UPLOAD_TYPE_FILE = 'upload-file';

    private $_user;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));

        User::observe(new UserObserver());

    }

    public function getGravatarImageUrl($email) {
        return $this->model->getGravatar($email);
    }

    public function findUserByHashedId($hashedUserId) {
        $userId = $this->model->unHashUserId( $hashedUserId );
        return $this->model->find($userId);
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function updateProfileAvatar( $params = [])
    {
        $data = [
            'user_id'     => $this->_user->id,
            'other_type'  => $params['upload_type'],
            'upload_type' => 'avatar'
        ];



        if ( $params['upload_type'] === self::UPLOAD_TYPE_FILE) {

            $file_data = $params['image_blob'];
            $file_name = 'profile-avatar.png'; //generating unique file name;

            @list($type, $file_data) = explode(';', $file_data);
            @list(, $file_data) = explode(',', $file_data);
            if($file_data!=""){ // storing image in storage/app/public Folder

                $avatarPath = "{$this->_user->hash_id}/my-avatar/{$file_name}";

                $data['path'] = $avatarPath;
                $data['name'] = $file_name;
                Storage::disk('user-uploads')->put($avatarPath,base64_decode($file_data));
            }
        }
        return $this->_user->myAvatar()
                    ->updateOrCreate([
                        'upload_type' => 'avatar'
                    ], $data);

    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function setLoggedInUser( $user )
    {
        $this->_user = $user;
        return $this;
    }

    public function getAllRoles() {

        $collections = Cache::rememberForever(config('user.user_roles_cache_keys'), function () {

            $roleModel   = app(EloquentRole::class);
            $collections = $roleModel->get();
            return $collections;
        });
        return $collections;
    }



    public function statusAction(User $model,  $identifier = null) {

        switch ($identifier) {
            case config('user.status.active'):
                return $this->sentinelAuth()->reActivate($model);
                break;
            case config('user.status.banned'):
                $this->suspendRepository()->unsuspend( $model );
                return $this->banRepository()->ban($model);
                break;
            case config('user.status.suspend'):
                $this->banRepository()->unban( $model );
                return $this->suspendRepository()->suspend($model);
                break;
            case config('user.status.unconfirmed'):

                $this->banRepository()->unban( $model );
                $this->suspendRepository()->unsuspend( $model );
                $this->sentinelAuth()->removeActivation($model);
                break;
        }

    }


    public function banRepository() {
        return app(\Modules\User\Checkpoints\Ban\BanRepository::class);
    }

    public function suspendRepository() {
        return app(\Modules\User\Checkpoints\Suspension\SuspensionRepository::class);
    }

    public function sentinelAuth() {
        return app(\Modules\User\Eloquent\Repositories\Authentication\SentinelAuth::class);
    }
}
