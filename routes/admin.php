<?php


Route::group(['prefix' => 'system-admin'], function () {

    require __DIR__ . "/admin/user.php";

    Route::group(['middleware' => 'dcm-admin.logged.in'], function () {

        $arrRoutes = [
            '/admin/artisan.php',
            '/admin/app.php',
            '/admin/page.php',
            '/admin/usermgt.php',
            '/admin/advertisement.php',
            '/admin/category.php',
            '/admin/configuration.php',
            '/admin/setup.php',

            // last part
            '/admin/dashboard.php',
        ];

        foreach($arrRoutes as $file)
            require __DIR__ . $file;

    });

});


