<?php

// using this pattern to used php artisan route:cache,
// instead of using router closure/grouping.
$setupRouteNameSpace = 'Admin\Setup';

#### START MenuController
##################################################################################
$menuController = "{$setupRouteNameSpace}\MenuController";

Route::get('setup/menu', "{$menuController}@getIndex")
    ->name('admin.setup.menu.index');

Route::resource('dcm-setup-menu-resource',$menuController)->only(['store']);

#### END MenuController
##################################################################################



#### START SliderController
##################################################################################
$sliderController = "{$setupRouteNameSpace}\SliderController";
Route::get('setup/slider', "{$sliderController}@getIndex")
    ->name('admin.setup.slider.index');

Route::resource('dcm-setup-slider-resource',$sliderController)->only(['store','destroy']);
#### END SliderController
##################################################################################



#### START SidebarController
##################################################################################
$sidebarController = "{$setupRouteNameSpace}\SidebarController";

Route::get('setup/sidebar', "{$sidebarController}@getIndex")
    ->name('admin.setup.sidebar.index');

#### END SidebarController
##################################################################################



#### START AdsPlacementController
##################################################################################
$adsPlacementController = "{$setupRouteNameSpace}\AdsPlacementController";

Route::get('setup/ads-placement', "{$adsPlacementController}@getIndex")
    ->name('admin.setup.adsplacement.index');

Route::resource('dcm-setup-ads-resource',$adsPlacementController)->only(['store']);
#### END AdsPlacementController
##################################################################################



#### START FooterController
##################################################################################
$footerController = "{$setupRouteNameSpace}\FooterController";

Route::get('setup/footer', "{$footerController}@getIndex")
    ->name('admin.setup.footer.index');

Route::resource('dcm-setup-footer-resource',$footerController)->only(['store']);
#### END footerController
##################################################################################