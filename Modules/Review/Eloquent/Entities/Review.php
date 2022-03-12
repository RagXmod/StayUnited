<?php

namespace Modules\Review\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Review.
 *
 * @package namespace Modules\Review\Eloquent\Entities;
 */
class Review extends Model implements Transformable
{
    use TransformableTrait;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reviewable()
    {
        return $this->morphTo();
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function author()
    {
        return $this->morphTo('author');
    }

    /**
     * @param Model $reviewable
     * @param $data
     * @param Model $author
     *
     * @return static
    */
    public function createReview(Model $reviewable, $data, Model $author): bool
    {
        $review = new static();
        $review->fill(array_merge($data, [
            'author_id'   => $author->id,
            'author_type' => get_class($author),
        ]));
        return (bool) $reviewable->reviews()->save($review);
    }

    /**
     * @param $id
     * @param $data
     *
     * @return mixed
     */
    public function updateReview($id, $data): bool
    {
        return (bool) static::find($id)->update($data);
    }

     /**
     * @param $id
     *
     * @return mixed
    */
    public function deleteReview($id): bool
    {
        return (bool) static::find($id)->delete();
    }

    /**
     * @param $id
     * @param $sort
     * @return mixed
     */
    public function getAllReviews($id, $sort = 'desc')
    {
        $rating = $this->select('*')
            ->where('reviewable_id', $id)
            ->orderBy('created_at', $sort)
            ->get();
        return $rating;
    }
    /**
     * @param $id
     * @param $sort
     * @return mixed
     */
    public function getApprovedReviews($id, $sort = 'desc')
    {
        $rating = $this->select('*')
            ->where('reviewable_id', $id)
            ->where('approved', true)
            ->orderBy('created_at', $sort)
            ->get();
        return $rating;
    }
    /**
     * @param $id
     * @param $sort
     * @return mixed
     */
    public function getNotApprovedReviews($id, $sort = 'desc')
    {
        $rating = $this->select('*')
            ->where('reviewable_id', $id)
            ->where('approved', false)
            ->orderBy('created_at', $sort)
            ->get();
        return $rating;
    }

}
