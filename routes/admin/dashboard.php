<?php



// using this pattern to used php artisan route:cache,
// instead of using router closure/grouping.

$dashboardRouteNameSpace = 'Admin\Dashboard';
$middlewareName     = 'dcm-admin.logged.in';

// sitemapController
$sitemapController = "{$dashboardRouteNameSpace}\SitemapController";
Route::get('dashboard/sitemap-generator', "{$sitemapController}@getIndex")->name('admin.dashboard.sitemap-generator');
Route::post('dashboard/generate-sitemap', "{$sitemapController}@postGenerateSitemap")->name('admin.dashboard.post.generate.sitemap');

// dashboardController
$dashboardController = "{$dashboardRouteNameSpace}\IndexController";

// authenticate user
Route::get('/', "{$dashboardController}@getIndex")->name('admin.dashboard.index');
