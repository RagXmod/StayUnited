<?php

namespace Modules\Configuration\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ConfigurationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("Modules\Configuration\Database\Seeders\ConfigurationTableSeeder");
    }
}
