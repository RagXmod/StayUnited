<?php

$pageRouteNameSpace = 'Web\Page';

Route::get('pages', "{$pageRouteNameSpace}\PageController@getIndex")->name('web.page.index');
Route::get('page/{identifier}', "{$pageRouteNameSpace}\PageController@getPage")->name('web.page.detail');
