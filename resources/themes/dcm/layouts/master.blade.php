<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        {{-- meta tags --}}
        @include('common.meta-tags')

        {{-- default link icons --}}
        @include('common.default-icons')

        {{-- default styling for all layouts. --}}
        @include('common.default-styles')
        {{-- push custom stylesheet based on page. --}}
        @stack('stylesheet')

        @include('common.cookie-enabled')

        {{-- override --}}
        <style>
            #page-footer {
                padding-top:2rem;
                min-height: auto !important;
            }
        </style>

        {{-- for search popup --}}
        <script>document.documentElement.className = 'js';</script>

        {{-- default schema --}}
        @include('common.schema.index')
        @stack('schema')


        @if( dcmConfig('app_custom_css') &&  dcmConfig('app_custom_css') != '')
            <style>
                {!!  dcmConfig('app_custom_css') !!}
            </style>
        @endif

        @if( dcmConfig('site_analytics') &&  dcmConfig('site_analytics') != '')
            {!!  dcmConfig('site_analytics') !!}
        @endif

    </head>
    <body>

        <div id="page-container" class="page-header-dark main-content-boxed">

            @include('common.header')
            <main id="main-container">
                @include('common.navigation')
                <div class="content content-full">

                    <div class="row">


                        @if( $has_sidebar ?? true )
                            <div class="col-lg-8 col-xl-8">
                                @yield('content')
                            </div>

                            <div class="col-lg-4 col-xl-4">
                                @include('web.sidebar.sidebar')
                            </div>
                        @else
                            <div class="col-lg-12 col-xl-12">
                                @yield('content')
                            </div>
                        @endif
                    </div>
                </div>
            </main>
            @include('common.footer')
        </div>

        {{-- search modal --}}
        @include('common.search-modal')

        {{-- default javascript --}}
        @include('common.default-javascript')


        @include('common.search-modal-javascript')


        {{-- push custom javascript --}}
        @stack('javascript')


        @if( dcmConfig('app_custom_js') &&  dcmConfig('app_custom_js') != '')
            <script type="text/javascript">
                {!!  dcmConfig('app_custom_js') !!}
            </script>
        @endif

        @if( dcmConfig('add_this') &&  dcmConfig('add_this') != '')
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid={{ dcmConfig('add_this') }}"></script>
        @endif
    </body>
</html>