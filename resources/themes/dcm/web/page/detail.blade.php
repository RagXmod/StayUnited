@extends( "layouts.master")

@section('content')

<div class="row">

   <div class="col-md-12">
      <div class="block block-themed">
         <div class="block-header bg-light">
            <h3 class="block-title">
                <a href="{{ route('web.home.index') }}">
                    {{ __('dcm.home') }}
                </a> »
                <a href="{{ route('web.page.index') }}">
                    {{ __('dcm.page') }}
                </a> » {{ $title ?? ''}} </h3>
         </div>
         <div class="block-content block-content-no-pad row mb-4">
                {!! $content ?? '' !!}
         </div>

      </div>

   </div>

</div>




@endsection


@push('javascript')

@endpush