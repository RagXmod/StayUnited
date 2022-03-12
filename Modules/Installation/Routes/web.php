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

Route::prefix('dcm')->group(function() {

    Route:: get('install/requirements', 'InstallationController@getRequirements')->name('dcm.install.requirements');
    Route:: get('install/permissions', 'InstallationController@getPermissions')->name('dcm.install.permissions');
    Route:: get('install/database', 'InstallationController@getDatabase')->name('dcm.install.database');
    Route:: get('install/start', 'InstallationController@startInstallation')->name('dcm.install.start');
    Route:: post('install/start', 'InstallationController@startInstallation')->name('dcm.install.start.post');
    Route:: post('install/processing', 'InstallationController@postInstallingApplication')->name('dcm.install.processing');

    Route:: get('install/complete', 'InstallationController@getComplete')->name('dcm.install.complete');
    Route:: get('install/error', 'InstallationController@getError')->name('dcm.install.error');

    Route::get('install', 'InstallationController@index')->name('dcm.install');
});