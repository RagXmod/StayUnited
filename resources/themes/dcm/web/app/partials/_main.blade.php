<div class="block-content block-content-no-pad row mb-4">
    <div class="col-12 col-sm-12 col-md-3 mb-5 app-image text-center">
        <img src="{{ $app->app_image_url }}" alt="{{ $app->title }}"
            style="padding: 5px;background: #f7f7f7;border-radius: 10px;width:170px;">
    </div>
    <div class="col-12 col-sm-12 col-md-9 mb-5 mt-1" style="padding: 0 25px;">
        <div>
            <h2>{{ $app->title }}</h2>
        </div>

        @if(isset($latest_version) && $latest_version != '')
            <div>
                <span itemprop="version"><span class="text-success">{{ $latest_version }}</span> {{  __('dcm.for_android') }}</span>
            </div>
        @endif
        <div>
            <div class="rating" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                <div class="stars-detail">
                <span class="score" title="{{ __('dcm.app_average_rating',['attr' => $app->title, 'rating' => $app->current_ratings ]) }}" style="width: {{ ceil(((double) $app->current_ratings / 5) * 100) }}%;"></span>
                </div>
                <meta itemprop="ratingCount" content="{{ $app->current_ratings }}">
                <meta itemprop="bestRating" content="5">
                <meta itemprop="worstRating" content="1">
            </div>
            <span class="star average" itemprop="ratingValue">{{ $app->current_ratings }}</span>
                <span class="details-delimiter"> | </span>
                <a href="#comment" class="details-to-bottom" data-type="reviews">{{ __('dcm.total_reviews', ['attr' => 0]) }}</a>
                <span class="details-delimiter"> | </span>
            <a href="#comment" class="details-to-bottom" data-type="posts">{{ __('dcm.total_comments', ['attr' => $app->comments->count()]) }}</a>
        </div>

        @if(isset($app->developer) && $app->developer->count() > 0)
            <div>
                <p itemprop="publisher">
                    {{ __('dcm.by_developer') }}
                    @foreach($app->developer as $developer)
                        <a title="{{ __('dcm.developer_title_tag', ['attr' => $developer->title ]) }}" href="{{ $developer->developer_detail_url }}">
                            {{ $developer->title }}
                        </a> @if(!$loop->last) | @endif
                    @endforeach
                </p>
            </div>
        @endif


        <div class="mt-3">
                <div class="btn-group mr-2 mb-2" role="group" aria-label="Icons File group">
                <a href="{{ $app->app_download_url }}" class="btn btn-hero-lg btn-square btn-hero-primary mr-1 mb-3">
                    <i class="fa fa-fw fa-download mr-1"></i> {{ __('dcm.download_apk_title') }}
                    @if(isset($latest_version_size) && $latest_version_size > 0) <small> ( {{ $latest_version_size }} )</small> @endif
                </a>
                <button type="button" class="btn btn-hero btn-square btn-light mr-1 mb-3">
                    <a class="btn" style="box-shadow: 0 0 0 0 transparent;" href="{{ route('web.app.detail.versions', $app->slug) }}"><i class="fa fa-fw fa-sort-numeric-up mr-1"></i> Versions</a>
                </button>
                </div>

        </div>

    </div>
</div>