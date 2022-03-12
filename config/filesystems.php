<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'logs' => [
            'driver' => 'local',
            'root' => storage_path('logs'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],


        'public-path' => [
            'driver' => 'local',
            'root' => public_path(),
            'url' => env('APP_URL').'/public',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],
        'user-uploads' => [
            'driver' => 'local',
            'root' => storage_path('app/public/user-uploads'),
            'url' => env('APP_URL').'/storage/user-uploads',
            'visibility' => 'public',
        ],

        'slider-uploads' => [
            'driver' => 'local',
            'root' => storage_path('app/public/slider-uploads'),
            'url' => env('APP_URL').'/storage/slider-uploads',
            'visibility' => 'public',
        ],
        'apk-uploads' => [
            'driver' => 'local',
            'root' => storage_path('app/public/apk-uploads'),
            'url' => env('APP_URL').'/storage/apk-uploads',
            'visibility' => 'public',
        ],
        'database' => [
            'driver' => 'local',
            'root' => base_path('database'),
        ],


        'modules' => [
            'driver' => 'local',
            'root' => base_path('Modules/'),
        ],

    ],

];
