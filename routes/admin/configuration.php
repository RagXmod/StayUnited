<?php

// using this pattern to used php artisan route:cache,
// instead of using router closure/grouping.

$configurationRouteNameSpace = 'Admin\Configuration';
// categoryController
$configurationController = "{$configurationRouteNameSpace}\ConfigurationController";

// app lists
Route::get('configuration/{type?}', "{$configurationController}@getIndex")->name('admin.configuration.index');


Route::resource('dcm-configuration-resource',$configurationController)->only(['index','destroy','update','store']);