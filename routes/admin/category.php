<?php

// using this pattern to used php artisan route:cache,
// instead of using router closure/grouping.

$categoryRouteNameSpace = 'Admin\Category';
// categoryController
$categoryController = "{$categoryRouteNameSpace}\CategoryController";

// app lists
Route::get('category', "{$categoryController}@getIndex")->name('admin.category.index');
Route::get('sub-category/{parent_id}', "{$categoryController}@getSubCategoryIndex")->name('admin.sub.category.index');

Route::get('category/edit/{id}', "{$categoryController}@getDetail")
        ->name('admin.category.detail');

Route::get('category/create', "{$categoryController}@getCreate")
        ->name('admin.category.create');


Route::resource('dcm-category-resource',$categoryController)->only(['index','destroy','update','store']);