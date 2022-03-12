<?php
error_reporting(0);
use Facades\App\Facades\ApiFacade;
use  Modules\Core\Support\Hashing\Obfuscator;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


Route::get('system/phpinfo', function() {

    echo phpinfo();
    exit;
});


Route::get('system/test-api', function() {

    // search apps
    // $searchTerm = request()->get('q', 'facebook');
    // $api = ApiFacade::search($searchTerm);
    // pre($api);

    // get details
    // $searchTerm = 'com.facebook.katana';
    // $api = ApiFacade::detail($searchTerm,[], 'admin');
    //  pre($api);
    // exit;
});


Route::get('system/storage-link', function() {

    if ( file_exists(public_path('storage')) )
        unlink(public_path('storage'));

    // Artisan::call('storage:link');
    Artisan::call('key:generate');
    Artisan::call('migrate');
    Artisan::call('db:seed');
    Artisan::call('module:seed');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('storage:link');
     pre('a');

   
    // $process = new Process('sh ../artisan-call.sh');
    // $process->run();

    // // executes after the command finishes
    // if (!$process->isSuccessful()) {
    //     throw new ProcessFailedException($process);
    // }

    // echo $process->getOutput();
    exit;

});