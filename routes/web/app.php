<?php


// using this pattern to used php artisan route:cache,
// instead of using router closure/grouping.

$appRouteNameSpace = 'Web\App';
$middlewareName    = 'dcm.logged.in';



$searchAppsController = "{$appRouteNameSpace}\SearchAppController";
Route::get('search-apps', "{$searchAppsController}@getSearch")->name('web.app.search');



// CategoryController
$categoryController = "{$appRouteNameSpace}\CategoryController";
Route::get('category/{slug}', "{$categoryController}@getDetail")->name('web.category.detail');



// DeveloperController
$developerController = "{$appRouteNameSpace}\DeveloperController";
Route::get('developer/{slug}', "{$developerController}@getDeveloper")->name('web.app.developer.detail');


// TagController
$tagController = "{$appRouteNameSpace}\TagController";
Route::get('tag/{slug}', "{$tagController}@getDetail")->name('web.app.tag.detail');

// ApkDetailController
$appController = "{$appRouteNameSpace}\ApkDetailController";

Route::get('{slug}/versions', "{$appController}@getAppVersions")->name('web.app.detail.versions');
Route::get('similar-apps/{slug}', "{$appController}@getSimilar")->name('web.app.detail.similar.apps');
Route::get('download/{slug}', "{$appController}@getDownload")->name('web.app.detail.download');

Route::post('generate-download-link', "{$appController}@postDownloadApk")->name('web.app.download.apk');

Route::get('{slug}', "{$appController}@getDetail")->name('web.app.detail');

