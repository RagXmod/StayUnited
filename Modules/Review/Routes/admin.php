<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('dcm-admin.logged.in')
    ->prefix('dcm/review')
    ->group(function() {

        // Route::resource('dcm-page-resource','PageController')->only(['index','destroy']);
        // Route::get('/', 'PageController@index')->name('dcm.page.index');

    });
