<header id="page-header">
    <div class="content-header">
        <div class="d-flex align-items-center">
            @include('common.logo')
        </div>
        <div>
            <div class="dropdown d-inline-block">
                @component('common.login-menu')
                    @slot('user_label_name')
                        {{ $user->full_name ?? __('dcm.login_or_register') }}
                    @endslot
                @endcomponent
            </div>
        </div>
    </div>
</header>