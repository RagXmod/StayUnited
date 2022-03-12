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
                            <p class="text-uppercase font-w700 font-size-sm text-muted">{{ __('dcm.password_reminder_label')}}</p>
                        </div>
                        <!-- END Header -->

                        <!-- Sign In Form -->
                        <form  action="{{ $url }}" method="POST">

                            @if(session('success') )
                                <div class="form-group">
                                    <span class="text-success">{{ session('success') }}</span>
                                </div>
                            @endif
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control form-control-alt {{ $errors->has('email') ? ' is-invalid' : '' }}"  id="email-username" name="email" placeholder="{{ __('dcm.email_placeholder')}}">
                                {!! $errors->first('email', '<span class="text-danger">:message</span>') !!}
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-hero-danger">
                                    <i class="fa fa-fw fa-sign-in-alt mr-1"></i> {{ __('dcm.password_reminder_label')}}
                                </button>
                            </div>
                            <hr/>
                            <div class="form-group">
                                <p class="mt-3 mb-0 d-lg-flex justify-content-lg-between">
                                    <a class="btn btn-sm btn-light d-block d-lg-inline-block mb-1" href="{{ route('web.user.index') }}" title="{{ __('dcm.sign_in')}}">
                                        <i class="fa fa-fw fa-sign-in-alt mr-1"></i> {{ __('dcm.sign_in')}}
                                    </a>
                                    <a class="btn btn-sm btn-secondary d-block d-lg-inline-block mb-1" href="{{ route('web.user.new-account') }}" title="{{ __('dcm.new_account')}}">
                                        <i class="fa fa-fw fa-sign-in-alt mr-1"></i> {{ __('dcm.new_account')}}
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
                                   {{ __('dcm.forgotpassword_label')}}
                                </p>
                                <a class="text-white-75 font-w600" href="#" title="{{ __('dcm.have_a_question')}}">
                                    {{ __('dcm.have_a_question')}}
                                </a>
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
