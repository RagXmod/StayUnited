@extends( "layouts.master-login")

@section('content')

<div class="row no-gutters justify-content-center ">
    <div class="hero-static col-sm-8 col-md-8 col-xl-8 d-flex align-items-center px-sm-0">

        <div class="col-md-12 col-xl-10" style="margin: 0 auto;">
            <div class="row no-gutters">
                <div class="col-md-6 order-md-1 bg-white">
                    <div class="block-content block-content-full px-lg-5 py-md-5 py-lg-6">
                        <!-- Header -->
                        <div class="mb-2 text-center">
                            <p> @include('common.logo')</p>
                            <p class="text-uppercase font-w700 font-size-sm text-muted"> {{ __('dcm.new_account')}} </p>
                        </div>
                        <!-- END Header -->

                        <!-- Sign In Form -->
                        <form  action="{{ $url }}" method="POST">

                            @if(session('error.message') )
                                <div class="form-group">
                                    <span class="text-danger">{{ session('error.message') }}</span>
                                </div>
                            @endif
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control form-control-alt {{ $errors->has('username') ? ' is-invalid' : '' }}"  id="username-username" name="username" placeholder="{{ __('dcm.username_placeholder')}}">
                                {!! $errors->first('username', '<span class="text-danger">:message</span>') !!}
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-alt {{ $errors->has('email') ? ' is-invalid' : '' }}"  id="email-email" name="email" placeholder="{{ __('dcm.email_placeholder')}}">
                                {!! $errors->first('email', '<span class="text-danger">:message</span>') !!}
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-alt {{ $errors->has('first_name') ? ' is-invalid' : '' }}"  id="first_name-first_name" name="first_name" placeholder="{{ __('dcm.firstname_placeholder')}}">
                                {!! $errors->first('first_name', '<span class="text-danger">:message</span>') !!}
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-alt {{ $errors->has('last_name') ? ' is-invalid' : '' }}"  id="last_name-last_name" name="last_name" placeholder="{{ __('dcm.lastname_placeholder')}}">
                                {!! $errors->first('last_name', '<span class="text-danger">:message</span>') !!}
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-alt {{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" placeholder="{{ __('dcm.password_placeholder')}}">
                                {!! $errors->first('password', '<span class="text-danger">:message</span>') !!}
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-hero-primary">
                                    <i class="fas fa-plus mr-1"></i> {{ __('dcm.sign_up')}}
                                </button>
                            </div>
                            <hr/>
                            <div class="form-group">
                                <p class="mt-3 mb-0 d-lg-flex justify-content-lg-between">
                                    <a class="btn btn-secondary btn-block d-block d-lg-inline-block mb-1" href="{{ route('web.user.index') }}" title="{{ __('dcm.sign_in')}}">
                                        <i class="fa fa-fw fa-sign-in-alt mr-1"></i> {{ __('dcm.sign_in')}}
                                    </a>
                                </p>
                            </div>
                        </form>
                        <!-- END Sign In Form -->
                    </div>
                </div>
                <div class="col-md-6 order-md-0 bg-primary-dark-op d-flex align-items-center">
                    <div class="block-content block-content-full px-lg-5 py-md-5 py-lg-6">
                        <div class="media">
                            <div class="media-body">
                                <p class="text-white font-w600 mb-1">
                                    {{ __('dcm.welcome_label')}}
                                </p>
                                <a class="text-white-75 font-w600" href="javascript:void(0)"> {{ __('dcm.have_a_question')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection


@push('javascript')

@endpush
