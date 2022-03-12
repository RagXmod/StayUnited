<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        {{-- meta tags --}}
        @include('admin.common.meta-tags')

        {{-- default link icons --}}
        @include('common.default-icons')

        {{-- default styling for all layouts. --}}
        @include('common.default-styles')
        {{-- push custom stylesheet based on page. --}}
        @stack('stylesheet')

        <style>
            #page-container.page-header-dark #page-header {
                background-color: #435450;
            }
            .nav-main-link:hover, .nav-main-link.active {
                color: #000;
                background-color: #e4e4e4;
            }
        </style>
    </head>
    <body>

        <div id="page-container" class="page-header-dark main-content-boxed">
            <main id="main-container">
                <div class="content content-full">
                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </main>
            {{-- @include('common.footer') --}}
        </div>

        {{-- default javascript --}}
        @include('common.default-javascript')



    </body>
</html>