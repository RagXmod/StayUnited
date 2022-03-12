<?php

// using this pattern to used php artisan route:cache,
// instead of using router closure/grouping.

$adsRouteNameSpace = 'Admin\Advertisement';
// adsController
$adsController = "{$adsRouteNameSpace}\AdsController";

// app lists
Route::get('advertisement', "{$adsController}@getIndex")->name('admin.ads.index');

Route::get('advertisement/edit/{id}', "{$adsController}@getDetail")
        ->name('admin.ads.detail');

Route::get('advertisement/create', "{$adsController}@getCreate")
        ->name('admin.ads.create');

Route::resource('dcm-ads-resource',$adsController)->only(['index','destroy','update','store']);