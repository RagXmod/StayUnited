<?php

namespace Modules\User\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

use Cache;
use Avatar;
use Storage;

/**
 * Class UserUpload.
 *
 * @package namespace Modules\User\Eloquent\Entities;
 */
class UserUpload extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'uploadable_id',
        'uploadable_type',
        'name',
        'path',
        'size',
        'upload_type',
        'other_type',
        'position'
    ];

    protected $appends = [
        'avatar_url'
    ];

    public function uploadable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(\Modules\User\Eloquent\Entities\User::class);
    }

    public function scopeTypeAvatar($query) {

        return $query->where('upload_type', $this->uploadTypeAvatar());
    }

    public function getAvatarUrlAttribute() {

        $image = '#';
        switch ($this->other_type) {
            case $this->uploadTypeNoPhoto():
                $image  = asset('img/profile.png');
                break;

            case $this->uploadTypeInitials():
                $image  = $this->createAvatarByInitials();
                $image = $image->encoded ?? $image;
                break;

            case $this->uploadTypeGravatar():
                $image  = $this->myGravatar();
                break;

            case $this->uploadTypeFile():
                $image  = $this->myUploadedPhotoAvatar();
                break;

            default:
                $image  = defaultProfileAvatar(); //can be found in start.php
                break;
        }
        return $image;
    }

    public function myGravatar($email = null)
    {

        if ( !$this->user && !$email)
            return '#';

        if( !$email ) {
            $email = $this->user->email;
        }

        return Cache::remember( $this->uploadTypeAvatar(), 1440, function() use($email) {
            $hash = hash('md5', strtolower(trim($email)));
            return sprintf("https://www.gravatar.com/avatar/%s?size=150", $hash);
        });
    }


    public function createAvatarByInitials($initialName = null)
    {

        if ( !$this->user && !$initialName)
            $name = 'DCM';

        $name = !$initialName ? $this->user->full_name : 'DCM';

        return Cache::remember( $this->uploadTypeInitials(), 1440, function() use($name) {
            return Avatar::create( $name )->toBase64();
        });
    }


    public function myUploadedPhotoAvatar()
    {
        return Storage::disk('user-uploads')->url($this->path);
    }

    public function uploadTypeAvatar() {
        return 'avatar';
    }

    public function uploadTypeNoPhoto() {
        return 'no-photo';
    }

    public function uploadTypeInitials() {
        return 'use-initials';
    }

    public function uploadTypeGravatar() {
        return  'gravatar';
    }

    public function uploadTypeFile() {
        return 'upload-file';
    }

}
