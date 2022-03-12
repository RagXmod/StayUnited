<?php

// using this pattern to used php artisan route:cache,
// instead of using router closure/grouping.

$appRouteNameSpace = 'Admin\App';
// appController
$appController = "{$appRouteNameSpace}\AppController";


// creating app page
Route::get('create-app', "{$appController}@getCreate")->name('admin.app.create');
Route::get('detail/{appid}', "{$appController}@getDetail")->name('admin.app.detail');
Route::get('create-app-from-store', "{$appController}@getCreateAppFromStore")->name('admin.app.create.from.store');


// app lists
Route::get('apps', "{$appController}@getIndex")->name('admin.app.index');



// featuredController
$featuredController = "{$appRouteNameSpace}\FeaturedAppController";
$featuredResource = "{$appRouteNameSpace}\Resources\FeaturedAppResource";

Route::get('featured-apps', "{$featuredController}@getIndex")
    ->name('admin.featured.apps');

Route::get('featured-app/edit/{id}', "{$featuredController}@getDetail")
        ->name('admin.featured.app.detail');

Route::get('featured-app/create', "{$featuredController}@getCreate")
        ->name('admin.featured.app.create');

Route::resource('dcm-featured-app-resource',$featuredResource)->only(['index','destroy','update','store']);


// Resources
$appResource = "{$appRouteNameSpace}\Resources\AppResource";
Route::post('dcm-app-resource/upload-apk',"{$appResource}@uploadApk")->name('admin.app.uploadapk');
Route::post('dcm-app-resource/delete-apk/{id}',"{$appResource}@deleteApk")->name('admin.app.deleteapk');
Route::resource('dcm-app-resource',$appResource)->only(['index','destroy','update','store']);




// api controller
$_apiController = "{$appRouteNameSpace}\ApiController";
Route::get('search-apps', "{$_apiController}@getSearch")->name('app.search');
Route::post('app-details', "{$_apiController}@postAppDetail")->name('app.details');

Route::post('create-apps-from-search',"{$_apiController}@postCreateAppsFromSearch")->name('api.app.create');
