<?php

namespace App\App\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\App\Eloquent\Observers\AppVersionObserver;

use CyrildeWit\EloquentViewable\Viewable;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;

/**
 * Class AppVersion.
 *
 * @package namespace App\App\Eloquent\Entities;
 */
class AppVersion extends Model implements Transformable, ViewableContract
{
    use TransformableTrait, Viewable;

    protected $removeViewsOnDelete = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'app_id',
        'identifier',
        'description',
        'file_path',
        'size',
        'original_name',
        'download_link',
        'is_link_external',
        'details',
        'position'
    ];

    protected $appends = [
        'date_formatted',
        'size_formatted',
        'description_formatted',
    ];

    public function app()
    {
        return $this->belongsTo(\App\App\Eloquent\Entities\App::class);
    }

    public function getDateFormattedAttribute() {
        return $this->created_at->format('Y-m-d');
    }

    public function getSizeFormattedAttribute() {
        return formattedFileSize( $this->size,true );
    }

    public function getDescriptionFormattedAttribute() {
        return str_limit( $this->description, 20, ' ...');
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

        AppVersion::observe(new AppVersionObserver());
    }
}
