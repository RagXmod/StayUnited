<?php


$webDcmMiddlewareName   = 'dcm.logged.in';
$adminDcmMiddlewareName = 'dcm-admin.logged.in';

return [
    'name' => 'User',

    /*
    |--------------------------------------------------------------------------
    | Define which route to redirect to after a successful login
    | its either route alias Ex. 'dcm.user.login' or full URL.. ex. http://dcm-modules.test/my-profile
    |--------------------------------------------------------------------------
    */
    'redirect_route_after_login' => url('/'),
    /*
    |--------------------------------------------------------------------------
    | Define which route the user should be redirected to after accessing
    | a resource that requires to be logged in
    |--------------------------------------------------------------------------
    */
    'redirect_route_not_logged_in' => 'dcm.user.login',
    /*
    |--------------------------------------------------------------------------
    | Login column(s)
    |--------------------------------------------------------------------------
    | Define which column(s) you'd like to use to login with, currently
    | only supported by the Sentinel user driver
    */
    'login-columns' => ['email'],

    /*
    |--------------------------------------------------------------------------
    | The default role for new user registrations
    |--------------------------------------------------------------------------
    | Default: User
    */
    'default_role' => 'User',


    'auth' => [

        'driver' => 'SentinelAuth',

        // put your custom login route alias here..
        // default value: null
        // override user login redirections.
        // 'default-login-routes' => ''

        'multiple_login_persistence' => true,


        'has_admin_permission_to_login' => 'user.can_login_in_admin'

    ],



    // admin middleare
    'admin_middleware_name' => $adminDcmMiddlewareName,

    // frontend
    'web_middleware_name' => $webDcmMiddlewareName,

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    | You can customise the Middleware that should be loaded.
    | The localizationRedirect middleware is automatically loaded for both
    | Backend and Frontend routes.
    */
    'middleware' => [
        'web' => [
            $webDcmMiddlewareName
        ],
        'admin' => [
            $adminDcmMiddlewareName
       	],
       	'api' => [

       	]
    ],

    'status' => [
        'active'      => 'active',
        'banned'      => 'banned',
        'suspend'     => 'suspend',
        'unconfirmed' => 'unconfirmed',
    ],


    // cache keys
    'user_status_cache_key' => 'user_status',
    'user_roles_cache_keys' => 'cache_user_roles'

];
