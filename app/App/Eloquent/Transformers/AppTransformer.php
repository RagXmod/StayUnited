<?php

namespace App\App\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use App\App\Eloquent\Entities\App;

/**
 * Class AppTransformer.
 *
 * @package namespace App\App\Eloquent\Transformers;
 */
class AppTransformer extends TransformerAbstract
{
    /**
     * Transform the App entity.
     *
     * @param \App\App\Eloquent\Entities\App $model
     *
     * @return array
     */
    public function transform(App $model)
    {
        return [
            'id'         => (int) $model->id,

            'title'                   => $model->title,
            'title_with_limit'        => str_limit($model->title, 25),
            'short_description'        => $model->short_description,
            'description'           => $model->description,
            'app_link'                => $model->app_link,
            'app_image_url'           => ($model->app_image_url) ? $model->app_image_url : $model->appImage->image_link,
            'current_ratings'         => $model->current_ratings,
            'total_ratings'           => $model->total_ratings,
            'star_ratings_percentage' => ceil(((double) $model->current_ratings / 5) * 100),
            'no_image_url'            => asset('img/default-app.png'),


            'admin_detail_link' => $model->admin_detail_url,


            'seo_title'         => $model->seo_title,
            'seo_keyword'       => $model->seo_keyword,
            'seo_description'   => $model->seo_description,

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
