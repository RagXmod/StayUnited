@extends( "layouts.master")

@section('content')

<div class="row">
   <div class="col-md-12 mb-5" itemscope itemtype="http://schema.org/MobileApplication">
      <div class="block block-themed">
         <div class="block-header bg-light">
            <h3 class="block-title"><a href="{{ url('/') }}">{{ __('dcm.home')}}</a> {{ __('dcm.breadcrumb_divider') }}
            <a href="{{ $app->app_detail_url }}">{{ $app->title }}</a>  {{ __('dcm.breadcrumb_divider') }} {{ __('dcm.download_title') }}</h3>
         </div>

        <div class="block-content block-content-no-pad row mb-4">
              <div class="col-12 col-sm-12 col-md-3 mb-5 app-image text-center">
                  <img src="{{ $app->app_image_url }}" alt="{{ $app->title }}"
                      style="padding: 5px;background: #f7f7f7;border-radius: 10px;width:120px;">
              </div>
              <div class="col-12 col-sm-12 col-md-9 mb-5 mt-1" style="padding: 0 25px;">
                <h3>{{ __('dcm.downloading_title', ['attr' => $app->title]) }}</h3>

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

                <p>
                  {!! str_limit($app->short_description ?? $app->description, 300)  !!}
                </p>
              </div>
        </div>


      </div>

      <div class="block block-rounded block-link-shadow text-center">

        <form id="download-app" method="POST" action="{{ route('web.app.download.apk') }}">
            @csrf

            <input type="hidden" name="app_id" value="{{ encrypt($app->slug) }}">
            <input type="hidden" name="version" value="{{ request()->get('version', 'latest') }}">

            <div class="block-content block-content-full bg-body-light">
              <h4 class="loading text-uppercase">{{ __('dcm.start_downloading_title') }} <span id="timer"></span> </h4>
            </div>
            <div class="block-content block-content-full">
                <p class="font-w500 mb-0 text-primary">
                    {{ __('dcm.preparing_apk_for_title',['attr' => $app->title]) }}
                </p>
                <p class="font-w600 mb-0">{{ __('dcm.if_download_start_title') }}
                  <a href="#" onclick="document.getElementById('download-app').submit();">{{ __('dcm.click_here_title') }}</a>
                </p>
            </div>

        </form>
      </div>

  </div>
  {{-- ads start --}}
  @include('common.ads-placement',[ 'identifier' => 'app-detail-page-top-leaderboard'])
  {{-- ads end --}}
  @include('web.app.partials._similar-to',['item' => $similar_apps])
  @include('web.app.partials._more-from-developer',['item' => $developer_apps, 'developer' => $developer])
  @include('web.app.partials._comment',['app' => $app])
</div>

@endsection


@push('javascript')

<script type="text/javascript">

  var startDl = false;
  var secondTitles = " {{ __('dcm.seconds_title') }}";

  function autoDownload()
  {
      jQuery(document).ready(function($) {
          $("#download-app").submit();
          window.close();
      });
  }

  var count = 15;
  function countDown(){
      var timer = document.getElementById("timer");
      if(count > 0){
          count--;
          timer.innerHTML = count + secondTitles;
          setTimeout(countDown, 1000);
      }else{

        autoDownload();
      }
  }
  countDown();
</script>


@endpush

@push('stylesheet')
<style>

/* loading dots */

.loading:after {
  content: ' . . .';
  animation: dots 1s steps(5, end) infinite;}

@keyframes dots {
  0%, 20% {
    color: rgba(0,0,0,0);
    text-shadow:
      .25em 0 0 rgba(0,0,0,0),
      .5em 0 0 rgba(0,0,0,0);}
  40% {
    color: '#000';
    text-shadow:
      .25em 0 0 rgba(0,0,0,0),
      .5em 0 0 rgba(0,0,0,0);}
  60% {
    text-shadow:
      .25em 0 0 '#000',
      .5em 0 0 rgba(0,0,0,0);}
  80%, 100% {
    text-shadow:
      .25em 0 0 '#000',
      .5em 0 0 '#000';}}
</style>
@endpush
