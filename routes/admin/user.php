<?php


// using this pattern to used php artisan route:cache,
// instead of using router closure/grouping.

$userRouteNameSpace = 'Admin\User';
$middlewareName     = 'dcm-admin.logged.in';

// UserController
$userController = "{$userRouteNameSpace}\UserController";

// authenticate user
Route::get('user/login', "{$userController}@getLogin")->name('admin.user.index');


Route::post('user/authenticate', "{$userController}@postAuthenticate")
        ->name('admin.user.authenticate');

// logout user
Route::get('user/logout', "{$userController}@logout")
        ->name('admin.user.logout');
