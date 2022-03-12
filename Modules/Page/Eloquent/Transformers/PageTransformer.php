<?php

namespace Modules\Page\Eloquent\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Page\Eloquent\Entities\Page;

/**
 * Class PageTransformer.
 *
 * @package namespace Modules\Page\Eloquent\Transformers;
 */
class PageTransformer extends TransformerAbstract
{
    /**
     * Transform the Page entity.
     *
     * @param \Modules\Page\Eloquent\Entities\Page $model
     *
     * @return array
     */
    public function transform(Page $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */
            'identifier'        => $model->identifier,
            'status_identifier' => $model->status_identifier,
            'page_id'           => $model->page_id,
            'slug'              => $model->slug,
            'title'             => $model->title,
            'content'           => $model->content,
            'seo_title'         => $model->seo_title,
            'seo_keyword'       => ($model->seo_keyword) ? arrayKeywordsToCommaString($model->seo_keyword): null,
            'seo_description'   => $model->seo_description,
            'is_enabled'        => $model->is_enabled,
            'link'              => $model->page_url,
            'icon'              => $model->icon,

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
