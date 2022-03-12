<?php


// disabled https via url...
Route::get('system/disabled-ssl', function() {

    $configurationModel = app(\Modules\Configuration\Eloquent\Repositories\ConfigurationRepositoryEloquent::class);
    $configurationModel
        ->makeModel()
        ->updateOrCreate([
            'identifier' => 'enable_ssl',
            'group'      => 'general',
        ], [
            'value' => 'no'
        ]);
    Artisan::call('cache:clear');
    return redirect()->to( url('/') );
});

Route::get('system/test', function() {

    $appModel          = app(\App\App\Eloquent\Entities\App::class);
    $appDeveloperModel = app(\App\App\Eloquent\Entities\AppDeveloper::class);


    // $model = $appDeveloperModel->with('apps')->where('identifier','facebook')->first();
    // pre($model->toArray());


   $app = $appModel->with('developer')->find(1);
   dd($app->toArray());

    exit;
});