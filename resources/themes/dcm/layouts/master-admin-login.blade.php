<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        {{-- meta tags --}}
        @include('common.meta-tags')

        {{-- default link icons --}}
        @include('common.default-icons')

        {{-- default styling for all layouts. --}}
        @include('common.default-styles')
    </head>
    <body>

        <div id="page-container" class="page-header-dark main-content-boxed">

            {{-- @include('common.header') --}}
            <main id="main-container">
                <div class="content-full">

                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            @yield('content')
                        </div>

                    </div>
                </div>
            </main>
            @include('common.footer')
        </div>

        {{-- default javascript --}}
        @include('common.default-javascript')

    </body>
</html>