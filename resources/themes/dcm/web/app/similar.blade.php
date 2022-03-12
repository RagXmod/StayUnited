@extends( "layouts.master")

@section('content')

<div class="row">

   <div class="col-md-12" itemscope itemtype="http://schema.org/MobileApplication">
      <div class="block block-themed">
        <div class="block-header bg-light">
            <h3 class="block-title"><a href="{{ url('/') }}">{{ __('dcm.home')}}</a> {{ __('dcm.breadcrumb_divider') }} {{ __('dcm.similar_to',['attr' => $app->title]) }} </h3>
        </div>

        <div class="block-content block-content-hover block-content-no-pad row mb-4">
            @if(isset($similar_apps) && !$similar_apps->isEmpty())
                  @foreach ($similar_apps as $app)
                     @include('web.app.partials._app',['item' => $app ])
                  @endforeach
            @else
                  {{-- <h2>No apps connected for this tags</h2> --}}
                  <h3 class="block-title mb-4 text-center">
                     {{ __('dcm.no_app_connected_to_tags')}}
                  </h3>
            @endif
        </div>
      </div>

   </div>

</div>




@endsection


@push('javascript')

@endpush
