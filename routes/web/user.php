<?php


// using this pattern to used php artisan route:cache,
// instead of using router closure/grouping.

$userRouteNameSpace = 'Web\User';
$middlewareName     = 'dcm.logged.in';

// UserController
$userController = "{$userRouteNameSpace}\UserController";

// authenticate user
Route::get('user/login', "{$userController}@getLogin")->name('web.user.index');
Route::post('user/authenticate', "{$userController}@postAuthenticate")
        ->name('web.user.authenticate');

// logout user
Route::get('user/logout', "{$userController}@logout")
        ->name('web.user.logout');

// forgot password
Route::get('user/forgot-password', "{$userController}@getForgotPassword")
        ->name('web.user.forgot-password');
Route::post('user/forgot-password', "{$userController}@postForgotPassword")
        ->name('web.user.post-forgot-password');

//  reset password
Route::get('user/reset-password/{hashId}/{resetcode}', "{$userController}@getResetPassword")
        ->name('web.user.reset-password');
Route::post('user/reset-password', "{$userController}@postResetPassword")
        ->name('web.user.post-reset-password');


// create new user account
Route::get('user/new-account', "{$userController}@getNewAccount")
        ->name('web.user.new-account');
Route::post('user/new-account', "{$userController}@postNewAccount")
        ->name('web.user.post-new-account');


// ProfileController
$profileController = "{$userRouteNameSpace}\ProfileController";
Route::get('user/profile', "{$profileController}@getProfile")
        ->name('web.user.profile')
        ->middleware($middlewareName);
Route::post('user/update-profile', "{$profileController}@postUpdateProfile")
        ->name('web.user.update.profile')
        ->middleware($middlewareName);

// update user avatar
Route::post('user/update-avatar/{hashId}', "{$profileController}@postUpdateAvatar")
        ->name('web.user.update.avatar')
        ->middleware($middlewareName);
