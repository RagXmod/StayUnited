<header id="page-header">
    <!-- Header Content -->
    <div class="content-header">
        <!-- Left Section -->
        <div class="d-flex align-items-center">

            @include('common.logo')
            {{-- data-toggle="layout" data-action="header_search_on" --}}
            <button id="btn-search" type="button" class="btn btn-dual ml-2">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <!-- END Left Section -->
        <!-- Right Section -->
        <div>
            <div class="dropdown d-inline-block">
                @component('common.login-menu')
                    @slot('user_label_name')
                        {{ $user->full_name ?? __('dcm.login_or_register') }}
                    @endslot
                @endcomponent
            </div>
        </div>
        <!-- END Right Section -->
    </div>
    <!-- END Header Content -->

    <!-- Header Search -->
    {{-- <div id="page-header-search" class="overlay-header bg-header-dark">
        <div class="content-header">
             <form class="w-100">
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="button" class="btn btn-primary" data-toggle="layout" data-action="header_search_off">
                        <i class="fa fa-fw fa-times-circle"></i>
                    </button>
                </div>
                <input id="search-apps" type="text" class="form-control" placeholder="{{ __('dcm.search_placeholder') }}" id="page-header-search-input" name="page-header-search-input">
            </div>
             </form>
        </div>
    </div> --}}
    <!-- END Header Search -->

    <!-- Header Loader -->
    {{-- <div id="page-header-loader" class="overlay-header bg-primary">
        <div class="content-header">
            <div class="w-100 text-center">
                <i class="fa fa-fw fa-2x fa-spinner fa-spin text-white"></i>
            </div>
        </div>
    </div> --}}
    <!-- END Header Loader -->
</header>