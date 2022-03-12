@extends( "layouts.master")

@section('content')

<div class="row">

   <div class="col-md-12" itemscope itemtype="http://schema.org/MobileApplication">
      <div class="block block-themed">
            <div class="block-header bg-light">
                <h3 class="block-title">
                    <a href="{{ url('/') }}">{{ __('dcm.home')}}</a> {{ __('dcm.breadcrumb_divider') }} {{ __('dcm.developer_title')}} {{ __('dcm.breadcrumb_divider') }} {{ $developer->title }}
                </h3>
            </div>

            <div class="block block-square ">
                <div class="block-content block-content-full">

                    <div class="row">
                        @foreach($apps as $app)

                            <div class="col-12 col-sm-12 col-md-2 mb-5 app-image text-center">
                                <img src="{{ $app->app_image_url }}" alt="{{ $app->title }}"
                                    style="padding: 2px;background: #f7f7f7;border-radius: 10px;width:80px;">
                            </div>
                            <div class="col-12 col-sm-12 col-md-10 mb-5 mt-1" style="padding: 0 25px;">
                                <div>
                                    <h4>{{ $app->title }}</h4>
                                </div>

                                <div>
                                    <div class="rating" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                                        <div class="stars-detail">
                                        <span class="score" title="{{ $app->title }} average rating {{ $app->current_ratings }}" style="width: {{ ceil(((double) $app->current_ratings / 5) * 100) }}%;"></span>
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

                                <div itemprop="description" id="app_description">
                                        {!! str_limit(strip_tags(@$app->description), 150) !!}
                                </div>

                                <div class="mt-3">
                                    <div class="btn-group mr-2 mb-2" role="group" aria-label="Icons File group">
                                        <a href="{{ $app->app_download_url }}" class="btn btn-success  btn-sm mr-1 mb-3">
                                            <i class="fa fa-fw fa-download mr-1"></i> {{ __('dcm.download_apk_title') }}
                                        </a>
                                        <a href="{{ $app->app_detail_url }}" title="{{ $app->title }}" class="btn btn-dark  btn-sm mr-1 mb-3">
                                            <i class="fa fa-fw fa-download mr-1"></i> {{ __('dcm.read_more') }}
                                        </a>
                                    </div>

                                </div>

                            </div>

                        @endforeach
                    </div>

                </div>
            </div>
        </div>
   </div>
</div>

@endsection


@push('javascript')

@endpush
