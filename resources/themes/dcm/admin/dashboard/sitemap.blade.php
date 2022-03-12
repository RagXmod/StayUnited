@extends( "layouts.master-admin")

@section('content')

@include('admin.dashboard.partials.navigation')

<div class="row animated fadeIn">

    <div class="col-lg-12 widget-content pb-5">

        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class=" pb-2 mb-2">
                <form method="POST" action="{{ route('admin.dashboard.post.generate.sitemap') }}">
                    @csrf
                    <input type="hidden" value="apps" name="sitemap_type" />
                    <button  type="submit" class="btn btn-sm btn-dark btn-block"> Generate sitemap for apps</button>
                </form>
            </h6>
            @if(isset($sitemapArray) && isset($sitemapArray['apps']) )
                <label>Apps main index url:
                    <a target="_blank" href="{{ $sitemapArray['apps']['url'] }}">
                        {{ $sitemapArray['apps']['url'] }}
                    </a>
                </label>
                <ul class="list-group">
                    @foreach($sitemapArray['apps']['sitemaps'] as $index => $item)
                        <li  class="list-group-item">
                        <a target="_blank" href="{{ $item }}">
                            <i class="fa fa-link mr-1"></i>  {{ $item }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class=" pb-2 mb-0">
                <form method="POST" action="{{ route('admin.dashboard.post.generate.sitemap') }}">
                    @csrf
                    <input type="hidden" value="categories" name="sitemap_type" />
                    <button  type="submit" class="btn btn-sm btn-dark btn-block">  Generate sitemap for categories</button>
                </form>
            </h6>
            @if(isset($sitemapArray) && isset($sitemapArray['categories']) )
                <label>Categories main index url:
                    <a target="_blank" href="{{ $sitemapArray['categories']['url'] }}">
                        {{ $sitemapArray['categories']['url'] }}
                    </a>
                </label>

                <ul class="list-group">
                    @foreach($sitemapArray['categories']['sitemaps'] as $index => $item)
                        <li  class="list-group-item">
                        <a target="_blank" href="{{ $item }}">
                            <i class="fa fa-link mr-1"></i>  {{ $item }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-center mt-2 mb-0"> No sitemaps found. Please generate one</p>
            @endif
        </div>

        <div class="my-3 p-3 bg-white rounded shadow-sm">
                <h6 class=" pb-2 mb-0">
                    <form method="POST" action="{{ route('admin.dashboard.post.generate.sitemap') }}">
                        @csrf
                        <input type="hidden" value="pages" name="sitemap_type" />
                        <button  type="submit" class="btn btn-sm btn-dark btn-block">  Generate sitemap for pages</button>
                    </form>
                </h6>
                @if(isset($sitemapArray) && isset($sitemapArray['pages']) )
                    <label>Pages main index url:
                        <a target="_blank" href="{{ $sitemapArray['pages']['url'] }}">
                            {{ $sitemapArray['pages']['url'] }}
                        </a>
                    </label>
                    <ul class="list-group">
                        @foreach($sitemapArray['pages']['sitemaps'] as $index => $item)
                            <li  class="list-group-item">
                            <a target="_blank" href="{{ $item }}">
                                <i class="fa fa-link mr-1"></i>  {{ $item }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-center mt-2 mb-0"> No sitemaps found. Please generate one</p>
                @endif
            </div>
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">
                <form method="POST" action="{{ route('admin.dashboard.post.generate.sitemap') }}">
                    @csrf
                    <input type="hidden" value="all" name="sitemap_type" />
                    <button  type="submit" class="btn btn-sm btn-dark btn-block"> Generate All Sitemaps</button>
                </form>
            </h6>


            @if( isset($isSitemapIndexExists) && $isSitemapIndexExists === true)
                {{-- <div class="media text-muted pt-3">
                    <p class="media-body pb-3 mb-0 small lh-125  text-center border-gray">
                        <a target="_blank" href="{{ url('sitemap/sitemap.xml')  }}">
                                {{ url('sitemap/sitemap.xml')  }}
                        </a>
                    </p>
                </div> --}}
                <label>Main sitemap index url</label>
                <ul class="list-group">
                    <li  class="list-group-item">
                        <a target="_blank" href="{{ url('/sitemap.xml')  }}">
                            <i class="fa fa-link mr-1"></i>  {{ url('sitemap.xml')  }}
                        </a>
                    </li>
                </ul>
             @else
                <p class="text-center mt-2 mb-0"> No sitemap.xml found. Please generate all</p>
            @endif

        </div>
    </div>

</div>


@endsection


@push('javascript')

@endpush