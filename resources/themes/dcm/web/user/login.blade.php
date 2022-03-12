@extends( "layouts.master-login")

@section('content')

<div class="row no-gutters justify-content-center ">
    <div class="hero-static col-sm-8 col-md-8 col-xl-8 d-flex align-items-center px-sm-0">

        <div class="col-md-12 col-xl-10" style="margin: 0 auto;">
            <div class="row no-gutters">
                <div class="col-md-6 order-md-1 bg-white">
                    <div class="block-content block-content-full px-lg-6 py-md-5 py-lg-6">
                        <!-- Header -->
                        <div class="mb-2 text-center">
                            <p> @include('common.logo')</p>
                            <p class="text-uppercase font-w700 font-size-sm text-muted">{{ __('dcm.sign_in')}}</p>
                        </div>
                        <!-- END Header -->

                        <!-- Sign In Form -->
                        <form  action="{{ $authenticate_url ?? route('web.user.authenticate') }}" method="POST">

                            @if(session('success-login') )
                                <div class="form-group">
                                    <span class="text-success">{{ session('success-login') }}</span>
                                </div>
                            @endif
                            @if(session('error.message') )
                                <div class="form-group">
                                    <span class="text-danger">{{ session('error.message') }}</span>
                                </div>
                            @endif
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control form-control-alt {{ $errors->has('login') ? ' is-invalid' : '' }}"  id="login-username" name="login" placeholder="{{ __('dcm.email_username_placeholder')}}">
                                {!! $errors->first('login', '<span class="text-danger">:message</span>') !!}
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-alt {{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" placeholder="{{ __('dcm.password_placeholder')}}">
                                {!! $errors->first('password', '<span class="text-danger">:message</span>') !!}
                            </div>

                            @if( dcmConfig('is_recaptcha') == 'yes' && dcmConfig('recaptcha_on_login') == 'yes'  && dcmConfig('recaptcha_site_key'))
                                <div class="form-group ">
                                    <div class="g-recaptcha" data-sitekey="{{ dcmConfig('recaptcha_site_key') }}"></div>
                                </div>
                            @endif

                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-hero-primary">
                                    <i class="fa fa-fw fa-sign-in-alt mr-1"></i> {{ __('dcm.sign_in')}}
                                </button>
                            </div>
                            <hr/>
                            <div class="form-group">
                                <p class="mt-3 mb-0 d-lg-flex justify-content-lg-between">
                                    <a class="btn btn-sm btn-light d-block d-lg-inline-block mb-1" href="{{ route('web.user.forgot-password') }}">
                                        <i class="fa fa-exclamation-triangle text-muted mr-1"></i> Forgot password
                                    </a>
                                    <a class="btn btn-sm btn-secondary d-block d-lg-inline-block mb-1" href="{{ route('web.user.new-account') }}">
                                        <i class="fa fa-fw fa-sign-in-alt mr-1"></i> New Account
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
                                    {{ __('dcm.signin_label')}}
                                </p>
                                <a class="text-white-75 font-w600" href="javascript:void(0)" title="{{ __('dcm.have_a_question')}}"> {{ __('dcm.have_a_question')}}</a>
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
    @if( dcmConfig('is_recaptcha') == 'yes' && dcmConfig('recaptcha_on_login') == 'yes'  && dcmConfig('recaptcha_site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endpush
