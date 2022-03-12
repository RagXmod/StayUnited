<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // demo purpose
        $this->call(DemoDatabaseSeeder::class);



        $this->call(ConfigurationSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(DefaultSeeder::class);
        $this->call(HomePageMenuSeeder::class);
        $this->call(HomePageFooterSeeder::class);
        $this->call(HomePageAdsSeeder::class);
    }
}
