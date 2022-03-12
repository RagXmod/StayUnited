<div class="bg-white">
    <div class="content">
        <!-- Toggle Main Navigation -->
        <div class="d-lg-none push">
            <!-- Class Toggle, functionality initialized in Helpers.coreToggleClass() -->
            <button type="button" class="btn btn-block btn-light d-flex justify-content-between align-items-center" data-toggle="class-toggle" data-target="#main-navigation" data-class="d-none">
                {{ __('dcm.menu')}}
                <i class="fa fa-bars"></i>
            </button>
        </div>
        <!-- END Toggle Main Navigation -->

        <!-- Main Navigation -->

        <div id="main-navigation" class="d-none d-lg-block push">
            <ul class="nav-main nav-main-horizontal nav-main-hover">
                @if ( isset($menuArr) && count($menuArr) > 0)
                    @each('common.partials.navigation-item', $menuArr, 'item')
                @else
                    <li class="nav-main-item">
                        <a class="nav-main-link active" href="{{ route('web.home.index') }}">
                            <span class="nav-main-link-name">
                                <i class="nav-main-link-icon fas fa-home"></i> HOME
                            </span>
                        </a>
                    </li>
                @endif

            </ul>
        </div>
        <!-- END Main Navigation -->
    </div>
</div>