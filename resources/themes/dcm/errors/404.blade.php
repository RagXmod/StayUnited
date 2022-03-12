@extends( "layouts.master")

@section('content')

<div id="page-container">
    <main id="main-container">
        <div class="bg-image" >
            <div class="hero bg-white-95">
                <div class="hero-inner">
                    <div class="content content-full">
                        <div class="px-3 py-5 text-center">
                            <div class="row " data-toggle="appear">
                                <div class="col-sm-6 text-center text-sm-right">
                                    <div class="display-1 text-danger font-w700">404</div>
                                </div>
                                <div class="col-sm-6 text-center d-sm-flex align-items-sm-center">
                                    <div class="display-1 text-muted font-w300">Error</div>
                                </div>
                            </div>
                            <h1 class="h2 font-w700 mt-5 mb-3 " data-toggle="appear" data-class="animated fadeInUp" data-timeout="300">Oops.. You just found an error page..</h1>
                            <h2 class="h3 font-w400 text-muted mb-5 " data-toggle="appear" data-class="animated fadeInUp" data-timeout="450">We are sorry but the page you are looking for was not found..</h2>
                            <div class="" data-toggle="appear" data-class="animated fadeInUp" data-timeout="600">
                                <a class="btn btn-hero-secondary" href="{{ url('/') }}">
                                    <i class="fa fa-arrow-left mr-1"></i> Back to Main Page
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection
