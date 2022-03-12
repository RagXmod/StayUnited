<button type="button" class="btn btn-dual" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-fw fa-user d-sm-none"></i>
    <span class="d-none d-sm-inline-block">
        {{ $user_label_name }}
    </span>
    <i class="fa fa-fw fa-angle-down ml-1 d-none d-sm-inline-block"></i>
</button>

<div class="dropdown-menu dropdown-menu-right p-0" aria-labelledby="page-header-user-dropdown">

    <div class="p-2">

        @auth
            <a class="dropdown-item" href="{{ route('web.user.profile') }}" title="{{ __('dcm.myprofile_label') }}">
                <i class="fa fa-fw fa-sign-in-alt mr-1"></i>{{ __('dcm.myprofile_label') }}
            </a>
            @if( $user->hasAccess( config('user.auth.has_admin_permission_to_login') ) )
                <a class="dropdown-item" href="{{ route('admin.dashboard.index') }}" title="{{ __('dcm.goto_admin_label') }}">
                    <i class="fa fa-fw fa-lock mr-1"></i> {{ __('dcm.goto_admin_label') }}
                </a>
            @endif
            <div role="separator" class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('web.user.logout') }}">
                <i class="far fa-fw fa-arrow-alt-circle-left mr-1"></i> {{ __('dcm.signout_label') }}
            </a>
        @endauth
        @guest
            <a class="dropdown-item" href="{{ route('web.user.index') }}" title="Sign-in">
                <i class="fa fa-fw fa-sign-in-alt mr-1"></i> {{ __('dcm.login_label') }}
            </a>
            <a class="dropdown-item" href="{{ route('web.user.new-account') }}" title="Register">
                <i class="fa fa-fw fa-user-plus mr-1"></i> {{ __('dcm.register_label') }}
            </a>
        @endguest
    </div>
</div>