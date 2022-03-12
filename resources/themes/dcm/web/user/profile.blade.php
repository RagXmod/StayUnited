@extends( "layouts.master")

@section('content')

<div class="container bootstrap snippet">


    <div class="block block-rounded block-bordered">
        <ul class="nav nav-tabs nav-tabs-block js-tabs-enabled" data-toggle="tabs" role="tablist">

            <li class="nav-item">
                <a class="nav-link active show" href="#btabs-static-profile">{{ __('dcm.myprofile_label') }}</a>
            </li>

        </ul>
        <div class="block-content tab-content">

            <div class="tab-pane active show" id="btabs-static-profile" role="tabpanel">

                <div class="row">
                    <div class="col-sm-3 mb-5"><!--left col-->

                        <div class="text-center">
                            <form method="POST" action="#">

                                <div class="avatar-wrapper">

                                    <div id="avatar"></div>

                                    <div class="text-center">
                                        <div class="avatar-preview" >
                                            <img class="avatar rounded-circle img-thumbnail img-responsive mt-3 mb-4" src="{{ $user->avatar }}" alt="{{ $user->full_name }}">
                                            <h5 class="text-muted">{{ __('dcm.myavatar_label') }}</h5>
                                        </div>
                                        <div id="changeMyPhoto" class="btn btn-outline-secondary btn-block mt-5 mb-4" >
                                            <i class="fa fa-camera"></i>
                                            {{ __('dcm.changephoto_label') }}
                                        </div>

                                        <div class="row avatar-controls d-none">
                                            <div class="col-md-12">
                                                <div id="use-photo" class="btn btn-outline-secondary btn-block" >
                                                    <i class="fa fa-camera"></i>
                                                    {{ __('dcm.usephoto_label') }}
                                                </div>
                                                <div  id="cancel-photo" class="btn btn-outline-secondary btn-block" >
                                                    <i class="fa fa-ban"></i>
                                                    {{ __('dcm.cancel_label') }}
                                                </div>
                                        </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="profileAvatarModal" tabindex="-1" role="dialog" aria-labelledby="profileAvatar" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="profileAvatar">{{ __('dcm.select_upload_photo_label') }}</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div data-profile-type="no-photo" class="col-md-3 text-center profile-photo">
                                                    <img src="{{ asset('img/profile.png') }}" class="rounded-circle img-thumbnail img-responsive">
                                                    <p class="mt-3">{{ __('dcm.nophoto_label') }}</p>
                                                </div>
                                                <div data-profile-type="use-initials"  class="col-md-3 text-center profile-photo">
                                                    <img src="{{ Avatar::create($user->full_name ?? 'DCM')->toBase64() }}" class="rounded-circle img-thumbnail img-responsive">
                                                    <p class="mt-3"> {{ __('dcm.useinitials_label') }}</p>
                                                </div>
                                                <div data-profile-type="gravatar"  class="col-md-3 text-center profile-photo">
                                                    <img src="{{ isset($myGravatar) ? $myGravatar : $user->avatar }}" class="rounded-circle img-thumbnail img-responsive">
                                                    <p class="mt-3"> {{ __('dcm.gravatar_label') }}  </p>
                                                </div>
                                                <div data-profile-type="my-avatar" class="col-md-3 text-center profile-photo">
                                                    <div class="btn btn-light btn-upload">
                                                        <i class="fas fa-upload"></i>
                                                        <input type="file" name="avatar" id="avatar-upload">
                                                    </div>
                                                    <p class="mt-3">{{ __('dcm.dragdrop_label') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!--/col-3-->
                    <div class="col-sm-9">

                        <div class="tab-content">
                            <div class="tab-pane active" id="home">

                                <form class="form" action="{{ route('web.user.update.profile') }}" method="POST" id="editprofilepage">
                                    @if(session('success') )
                                        <div class="form-group">
                                            <span class="text-success">{{ session('success') }}</span>
                                        </div>
                                    @endif
                                    @csrf
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="first_name"><h4>{{ __('dcm.firstname_label') }}</h4></label>
                                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="{{ __('dcm.firstname_placeholder') }}" title="{{ __('dcm.firstname_placeholder') }}" value="{{ $user->first_name}}">
                                            {!! $errors->first('first_name', '<span class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <div class="col-xs-6">
                                            <label for="last_name"><h4>{{ __('dcm.lastname_label') }}</h4></label>
                                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="{{ __('dcm.lastname_placeholder') }}" title="{{ __('dcm.lastname_placeholder') }}" value="{{ $user->last_name}}">
                                            {!! $errors->first('last_name', '<span class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <div class="col-xs-6">
                                            <label for="email"><h4>{{ __('dcm.email_label') }}</h4></label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="{{ __('dcm.email_placeholder') }}" title="{{ __('dcm.email_placeholder') }}" value="{{ $user->email}}">
                                            {!! $errors->first('email', '<span class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <div class="col-xs-6">
                                            <label for="username"><h4>{{ __('dcm.username_label') }}</h4></label>
                                            <input type="username" class="form-control" name="username" id="username" placeholder="{{ __('dcm.username_placeholder') }}" title="{{ __('dcm.username_placeholder') }}" value="{{ $user->username}}">
                                            {!! $errors->first('username', '<span class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <hr/>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="password"><h4>{{ __('dcm.current_password') }}</h4></label>
                                            <input type="password" class="form-control" name="old_password" id="old_password" placeholder="***********" title="enter your old password.">
                                            {!! $errors->first('old_password', '<span class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                        <label for="password_confirmation"><h4>{{ __('dcm.newpassword_palceholder') }}</h4></label>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="***********" title="{{ __('dcm.newpassword_palceholder') }}">
                                            {!! $errors->first('password', '<span class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                            <div class="col-xs-6">
                                                <label for="password_confirmation"><h4>{{ __('dcm.confirmpassword_placeholder') }}</h4></label>
                                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="***********" title="{{ __('dcm.confirmpassword_placeholder') }}">

                                            </div>
                                        </div>
                                    <div class="form-group">
                                            <div class="col-xs-12">
                                                <br>

                                                <button class="btn btn-lg btn-success" type="submit"><i class="fa fa-save"></i> {{ __('dcm.save_label') }}</button>
                                                <a class="btn btn-lg btn-secondary" href="{{ route('web.home.index') }}" ><i class="fa fa-arrow-right"></i> {{ __('dcm.go_homepage') }}</a>
                                            </div>
                                    </div>
                                </form>

                                </div><!--/tab-pane-->
                        </div>
                    </div><!--/col-9-->
                </div>

            </div>

        </div>
    </div>





@endsection


@push('stylesheet')
<link rel="stylesheet" id="css-profile" href="{{ mix('css/profile.css') }}">
@endpush

@push('javascript')

<script src="{{ mix('js/profile.js') }}"></script>
<script>


    $(document).ready(function(){
        window.myProfile.setUrl("{{ $post_update_avatar_url ?? '#' }}");
    });
</script>
@endpush
