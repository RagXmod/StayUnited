<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Sentinel;
use DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::table('activations')->truncate();
        DB::table('throttle')->truncate();
        DB::table('role_users')->truncate();
        DB::table('user_details')->truncate();
        $role = [

            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'permissions' => [
                    'user.superadmin'         => true,
                    'user.admin'              => true,
                    'user.can_login_in_admin' => true,
                    'user.can_login_in_frontend' => true
                ]
            ],
            [
                'name' => 'Administrator',
                'slug' => 'administrator',
                'permissions' => [
                    'user.superadmin'         => false,
                    'user.admin'              => true,
                    'user.can_login_in_admin' => true,
                    'user.can_login_in_frontend' => true
                ]
            ],
            [
                'name' => 'Moderator',
                'slug' => 'moderator',
                'permissions' => [
                    'user.can_login_in_admin' => true,
                    'user.can_login_in_frontend' => true
                ]
            ],
            [
                'name' => 'Pro User',
                'slug' => 'pro-user',
                'permissions' => [
                    'user.can_login_in_frontend' => true
                ]
            ],
            [
                'name' => 'Regular',
                'slug' => 'regular',
                'permissions' => [
                    'user.can_login_in_frontend' => true
                ]
            ]
        ];

        $superAdmin = [
            'email'    => 'superadmin@dcm.com',
            'username' => 'superadmin',
            'password' => 'password',
            'first_name' => 'The',
            'last_name' => 'Creator',
            'permissions' => [

            ]
        ];

        $adminOnly = [
            'email'    => 'admin@dcm.com',
            'username' => 'admin',
            'password' => 'hello2019',
            'first_name' => 'Admin',
            'last_name' => 'Only',
            'is_test_mode_account' => 1,
            'permissions' => [
            ]
        ];

        $user = [
            'email'    => 'demo101@dcm.com',
            'username' => 'demo2018',
            'password' => 'demo2018',
            'first_name' => 'John',
            'last_name' => 'Doe-nut'
        ];


        foreach ($role as $key => $groupname) {
            Sentinel::getRoleRepository()->createModel()->create( $groupname );
        }


        $allRoles = Sentinel::getRoleRepository()->all();
        foreach ($allRoles as $key => $role) {
            if($role->slug == 'super-admin')
            {
                $superAdminUser = Sentinel::registerAndActivate($superAdmin);
                $superAdminUser->roles()->attach($role);

                $superAdminUser->userDetail()->updateOrCreate(['user_id' => $superAdminUser->id],['about_me' => 'Welcome '. $superAdminUser->full_name]);
            }

            if($role->slug == 'administrator') {
                $adminOnlyUser = Sentinel::registerAndActivate($adminOnly);
                $adminOnlyUser->roles()->attach($role);
                $adminOnlyUser->userDetail()->updateOrCreate(['user_id' => $adminOnlyUser->id],['about_me' => 'Welcome '. $adminOnlyUser->full_name]);
            }

            if($role->slug == 'regular')
            {
                $regularUser = Sentinel::registerAndActivate($user);
                $regularUser->roles()->attach($role);
                $regularUser->userDetail()->updateOrCreate(['user_id' => $regularUser->id],['about_me' => 'Welcome '. $regularUser->full_name]);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


    }
}
