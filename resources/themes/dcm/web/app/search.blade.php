@extends( "layouts.master")

@section('content')

<div class="row">

    <div class="col-md-12" itemscope itemtype="http://schema.org/MobileApplication">
        <div class="block block-themed">

            <div class="bg-white">
                <div class="content">
                    <!-- Search -->
                    <form class="push" action="{{ route('web.app.search')  }}">
                        <div class="input-group input-group-lg">
                            <input type="text" name="q" class="form-control form-control-alt" placeholder="Search apps">
                            <div class="input-group-append">
                                <span class="input-group-text border-0 bg-body">
                                    <i class="fa fa-fw fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </form>
                    <!-- END Search -->
                </div>
            </div>

            <div class="block-content p-4">
                <h4 class="border-bottom border-gray pb-2 mb-0"> {!! __('dcm.search_result_title', ['attr' => $searchResults->count(), 'search' => $searchInput ?? '']) !!}</h4>
            </div>

            @if(!$searchResults->isEmpty())
                @foreach($searchResults->groupByType() as $type => $modelSearchResults)
                    <div class="block-content p-4">
                        <strong class="mt-2 pb-2 mb-0"> <span >{{ ucwords($type) }}</span> results.</strong>
                        @foreach($modelSearchResults as $searchResult)
                            <div class="media text-muted pt-3">
                                <img src="{{ $searchResult->searchable->app_image_url ?? asset('img/default-app.png') }}" class="mr-3" alt="{{ __('dcm.download_apk_title') }} - {{ $searchResult->title }}" width="30px">
                                {{--  --}}
                                <div class="media-body">
                                    <h6 class="mt-0">
                                        <a href="{{ $searchResult->url }}" title="{{ __('dcm.download_apk_title') }} - {{ $searchResult->title }}">
                                            <i class="fab fa-android"></i> {{ $searchResult->title }}
                                        </a>
                                        <a  href="{{ $searchResult->url }}" title="{{ __('dcm.download_apk_title') }} - {{ $searchResult->title }}"> <i class="fa fa-download text-dark" style="float:right;"></i> </a>
                                    </h6>
                                    <p >{{ str_limit(e($searchResult->searchable->description), 200) }} </p>

                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="media text-muted pt-3 ">
                    <div class="media-body mb-4 text-center">
                        {{ __('dcm.no_result') }}
                    </div>
                </div>
            @endif
        </div>

    </div>

</div>




@endsection


@push('javascript')

@endpush
