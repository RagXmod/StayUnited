@extends( "layouts.master")

@section('content')

<div class="row">

   <div class="col-md-12" itemscope itemtype="http://schema.org/MobileApplication">
      <div class="block block-themed">
         <div class="block-header bg-light">
         <h3 class="block-title"><a href="{{ url('/') }}">{{ __('dcm.home')}}</a> {{ __('dcm.breadcrumb_divider') }}
            <a href="{{ $app->app_detail_url }}">{{ $app->title }}</a>  {{ __('dcm.breadcrumb_divider') }} {{ __('dcm.version_title') }}</h3>
         </div>

         @include('web.app.partials._main',['app' => $app])
      </div>

    @include('web.app.partials._version',['app' => $app])
   </div>

   {{-- ads start --}}
   @include('common.ads-placement',[ 'identifier' => 'app-detail-page-top-leaderboard'])
   {{-- ads end --}}
   @include('web.app.partials._comment',['app' => $app])
</div>




@endsection


@push('javascript')

@endpush
