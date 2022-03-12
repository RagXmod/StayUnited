@extends( "layouts.master-error")

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
                                    <div class="display-1 text-danger font-w700">500</div>
                                </div>
                                <div class="col-sm-6 text-center d-sm-flex align-items-sm-center">
                                    <div class="display-1 text-muted font-w300">Error</div>
                                </div>
                            </div>
                            <h1 class="h2 font-w700 mt-5 mb-3 " data-toggle="appear" data-class="animated fadeInUp" data-timeout="300">Oops.. You just found an error page..</h1>
                            <h2 class="h3 font-w400 text-muted mb-5 " data-toggle="appear" data-class="animated fadeInUp" data-timeout="450">We are sorry but your request contains bad syntax and cannot be fulfilled..</h2>
                            @if(isset($is_db_error))
                            <p class="h4 font-w500 mt-5 mb-3 ">
                                Please check your database, incase you accidentally remove your database tables. <br/>  Don`t worry just do the option below <br/>
                                <ul  class="list-group">
                                    <li class="list-group-item">Please remove ".env" file to re-install</li>
                                </ul>
                                <pre>

                                </pre>
                                <p>

                                <button class="btn btn-danger btn-block" type="button" data-toggle="collapse" data-target="#errorMessage" aria-expanded="true" aria-controls="errorMessage">
                                    Hide Error Message
                                </button>
                                </p>
                                <div class="collapse show" id="errorMessage">
                                <div class="card card-body">
                                    {{ pre($message) }}
                                </div>
                                </div>
                            </p>
                            @endif
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