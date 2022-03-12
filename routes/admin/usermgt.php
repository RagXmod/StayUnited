<?php

// using this pattern to used php artisan route:cache,
// instead of using router closure/grouping.
$userRouteNameSpace = 'Admin\User';

// userMgtController
$userMgtController = "{$userRouteNameSpace}\UserMgtController";

// authenticate user
Route::get('users', "{$userMgtController}@getIndex")
    ->name('admin.user.index');


Route::get('user/edit/{id}', "{$userMgtController}@getDetail")
        ->name('admin.user.detail');

Route::get('user/create', "{$userMgtController}@getCreate")
        ->name('admin.user.create');

Route::resource('dcm-user-mgt-resource',$userMgtController)->only(['index','destroy','update','store']);