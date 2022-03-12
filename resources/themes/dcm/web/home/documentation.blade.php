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

    </head>
    <body>

        <div id="page-container" class="page-header-dark main-content-boxed">
            @include('common.header')
            <main id="main-container">
               
                <div class="content content-full">

                    <div class="row">
                      <iframe src="http://googelplayappstore.info/documentations/#/" style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;">
                        Your browser doesn't support iframes
                    </iframe>
                    </div>
                </div>
            </main>
            @include('common.footer')
        </div>
       
    </body>
</html>