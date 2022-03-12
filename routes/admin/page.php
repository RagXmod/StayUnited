<?php

// using this pattern to used php artisan route:cache,
// instead of using router closure/grouping.
$pageRouteNameSpace = 'Admin\Page';

// pageController
$pageController = "{$pageRouteNameSpace}\PageController";


// authenticate user
Route::get('pages', "{$pageController}@getIndex")
    ->name('admin.page.index');


Route::get('page/edit/{id}', "{$pageController}@getDetail")
        ->name('admin.page.detail');

Route::get('page/create', "{$pageController}@getCreate")
        ->name('admin.page.create');

Route::resource('dcm-page-resource',$pageController)->only(['index','destroy','update','store']);