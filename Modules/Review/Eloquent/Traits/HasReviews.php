<?php


namespace Modules\Review\Eloquent\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait HasReviews.
 *
 * @package namespace Modules\Review\Eloquent\Traits;
 */
trait HasReviews
{

    public function reviews(): MorphMany
    {
        return $this->morphMany(config('review.model'), 'reviewable');
    }

    public function createReview($data, Model $author, Model $parent = null): bool
    {
        return $this->getReviewModel()->createReview($this, $data, $author);
    }

    public function updateReview($id, $data, Model $parent = null): bool
    {
        return $this->getReviewModel()->updateReview($id, $data);
    }


    public function deleteReview($id): bool
    {
        return $this->getReviewModel()->deleteReview($id);
    }

      /**
     *
     * @var $round
     * @return mixed
     */
    public function getRatingAverage($round = true)
    {
      if ($round === true) {
            return $this->reviews()
                ->selectRaw('ROUND(AVG(rating), '.$round.') as average_rating')
                ->pluck('average_rating');
        }
        return $this->reviews()
                ->selectRaw('AVG(rating) as average_rating')
                ->pluck('average_rating');
    }


    /**
     *
     * @return mixed
     */
    public function countReviews()
    {
      return $this->reviews()
                ->selectRaw('count(rating) as total_reviews')
                ->pluck('total_reviews');
    }


     /**
     * @param $max
     *
     * @return mixed
     */
    public function ratingPercent($max = 5)
    {
        $reviews  = $this->reviews();
        $quantity = $reviews->count();
        $total    = $reviews->selectRaw('SUM(rating) as total')->pluck('total');
        return ($quantity * $max) > 0 ? $total / (($quantity * $max) / 100) : 0;
    }



    protected function getReviewModel(): Model
    {
        $model = config('laravel-reviewable.models.review');
        return new $model();
    }
}