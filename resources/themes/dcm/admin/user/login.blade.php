@extends( "layouts.master-admin-login")

@section('content')

<div class="row no-gutters justify-content-center ">
    <div class="hero-static col-sm-8 col-md-8 col-xl-8 d-flex align-items-center px-sm-0">

        <div class="col-md-12 col-xl-12" style="margin: 0 auto;">
            <div class="row no-gutters">
                <div class="col-md-6 order-md-1 bg-white" style="margin: 0 auto;">
                    <div class="block-content block-content-full px-lg-6 py-md-5 py-lg-6">

                        <div class="mb-2 text-center">
                            <p> @include('common.logo')</p>
                            <p class="text-uppercase font-w700 font-size-sm text-muted">{{ __('dcm.sign_in')}}</p>
                        </div>

                        <form  action="{{ route('admin.user.authenticate') }}" method="POST">

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
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-hero-primary">
                                    <i class="fa fa-fw fa-sign-in-alt mr-1"></i> {{ __('dcm.sign_in')}}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection


@push('javascript')

@endpush
