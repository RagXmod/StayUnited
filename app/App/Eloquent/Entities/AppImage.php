<?php

namespace App\App\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use App\App\Eloquent\Observers\AppImageObserver;
use Prettus\Repository\Traits\TransformableTrait;
use Storage;

/**
 * Class AppImage.
 *
 * @package namespace App\App\Eloquent\Entities;
 */
class AppImage extends Model implements Transformable
{
    use TransformableTrait;

    public $uploadFileType    = 'screenshots';
    public $uploadLinkType    = 'link';
    public $uploadAppLogoType = 'app_image';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'app_imageable_id',
        'app_imageable_type',
        'file_path',
        'image_url',
        'size',
        'original_name',
        'upload_type',
        'position'
    ];


    protected $appends = [
        'image_link'
    ];


    /**
     * getImageLinkAttribute
     *
     * @access  public
     */
    public function getImageLinkAttribute() {

        $image = asset('img/default-app.png');
        if( in_array($this->upload_type, [$this->uploadFileType, $this->uploadAppLogoType]) )
            $image =  Storage::disk('apk-uploads')->url($this->file_path);
        elseif($this->upload_type == $this->uploadLinkType )
            $image =  $this->image_url;

        return $image;

    }

    public function app_imageable()
    {
        return $this->morphTo();
    }

    public function cacheKeyArray($key = null) {
        $data = [
            'tbl_name' => $this->getTable()
        ];

        if($key)
            return isset($data[$key]) ? $data[$key] : '';

        return $data;
    }


    protected static function boot() {
        parent::boot();

        AppImage::observe(new AppImageObserver());
    }

}
