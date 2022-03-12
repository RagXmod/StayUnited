<div class="bg-white">
        <div class="content">
            <div class="d-lg-none push">
                <button type="button" class="btn btn-block btn-light d-flex justify-content-between align-items-center" data-toggle="class-toggle" data-target="#main-navigation" data-class="d-none">
                    {{ __('dcm.menu')}}
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <div id="main-navigation" class="d-none d-lg-block push">
                <ul class="nav-main nav-main-horizontal nav-main-hover">

                    <li class="nav-main-item">
                    <a class="nav-main-link text-uppercase" href="{{ route('admin.dashboard.index') }}">
                            <span class="nav-main-link-name">
                                <i class="nav-main-link-icon fas fa-home"></i> Dashboard
                            </span>
                        </a>
                    </li>

                    <li class="nav-main-item">
                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                            <span class="nav-main-link-name text-uppercase">
                                <i class="nav-main-link-icon fa fa-cogs"></i> Setup
                            </span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link text-uppercase" href="{{ route('admin.setup.menu.index') }}">
                                    <span class="nav-main-link-name">
                                        <i class="nav-main-link-icon fa fa-sitemap"></i> Menu
                                    </span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link text-uppercase" href="{{ route('admin.setup.slider.index') }}">
                                    <span class="nav-main-link-name">
                                        <i class="nav-main-link-icon fa fa-images"></i> Slider
                                    </span>
                                </a>
                            </li>

                            <li class="nav-main-item">
                                <a class="nav-main-link text-uppercase" href="{{ route('admin.setup.adsplacement.index') }}">
                                    <span class="nav-main-link-name">
                                        <i class="nav-main-link-icon fa fa-money-bill-alt"></i> Ads Placement
                                    </span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link text-uppercase" href="{{ route('admin.setup.footer.index') }}">
                                    <span class="nav-main-link-name">
                                        <i class="nav-main-link-icon fa fa-list-alt"></i> Footer
                                    </span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link text-uppercase" href="{{ route('admin.dashboard.sitemap-generator') }}">
                                    <span class="nav-main-link-name">
                                        <i class="nav-main-link-icon fa fa-sitemap"></i> Generate Sitemap
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-main-item">
                    <a class="nav-main-link text-uppercase" href="{{ route('admin.app.index') }}">
                            <span class="nav-main-link-name">
                                    <i class="nav-main-link-icon fab fa-android"></i> Apps
                            </span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                    <a class="nav-main-link text-uppercase" href="{{ route('admin.user.index') }}">
                            <span class="nav-main-link-name">
                                <i class="nav-main-link-icon fas fa-users-cog"></i> User Mgt.
                            </span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link text-uppercase" href="{{ route('admin.category.index') }}">
                            <span class="nav-main-link-name">
                                <i class="nav-main-link-icon fa fa-flag-usa"></i> Categories
                            </span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link text-uppercase" href="{{ route('admin.page.index') }}">
                            <span class="nav-main-link-name">
                                <i class="nav-main-link-icon fas fa-book"></i> Pages
                            </span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link text-uppercase" href="{{ route('admin.ads.index') }}">
                            <span class="nav-main-link-name">
                                <i class="nav-main-link-icon fas fa-dollar-sign"></i> Advertisement
                            </span>
                        </a>
                    </li>


                    <li class="nav-main-item">
                        <a class="nav-main-link text-uppercase" href="{{ route('admin.configuration.index') }}">
                            <span class="nav-main-link-name">
                                <i class="nav-main-link-icon fas fa-tools"></i> Configurations
                            </span>
                        </a>
                    </li>
                    {{-- <li class="nav-main-item">
                        <a class="nav-main-link text-uppercase" href="#">
                            <span class="nav-main-link-name">
                                <i class="nav-main-link-icon fas fa-cubes"></i> Modules
                            </span>
                        </a>
                    </li> --}}

                    {{-- <li class="nav-main-item">
                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                            <span class="nav-main-link-name text-uppercase">
                                <i class="nav-main-link-icon fas fa-cogs"></i> Settings
                            </span>
                        </a>
                        <ul class="nav-main-submenu nav-main-submenu-right">

                            <li class="nav-main-item">
                                <a class="nav-main-link text-uppercase" href="#">
                                    <span class="nav-main-link-name">
                                        <i class="nav-main-link-icon fa fa-cog"></i> General
                                    </span>
                                </a>
                            </li>

                        </ul>
                    </li> --}}

                </ul>
            </div>
        </div>
    </div>