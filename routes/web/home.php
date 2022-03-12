<?php

$homeRouteNameSpace = 'Web\Home';

Route::get('documentations', function() {

  return view('web.home.documentation');

  // return '<iframe src="http://googelplayappstore.info/documentations/#/"></iframe>';
  
});


// contact us
Route::get('contact-us', "{$homeRouteNameSpace}\ContactUsController@getContactUs")->name('web.home.contactus');
Route::post('contact-us', "{$homeRouteNameSpace}\ContactUsController@postContactUs")->name('web.home.contactus.post');

// Report Content
Route::get('report-content', "{$homeRouteNameSpace}\ReportAbuseContentController@getReportContent")->name('web.home.reportcontent');
Route::post('report-content', "{$homeRouteNameSpace}\ReportAbuseContentController@postReportContent")->name('web.home.reportcontent.post');

Route::get('latest/{slug}', "{$homeRouteNameSpace}\HomeController@getLatest")->name('web.home.latest');

Route::get('/', "{$homeRouteNameSpace}\HomeController@getIndex")->name('web.home.index');