<?php

use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $storage = Storage::disk('database');
        $jsonPath = 'json-files/';
        $jsonObj  = $storage->get($jsonPath.'default-configuration.json');
        $jsonObj  = json_decode($jsonObj, true);

        if ( $jsonObj && isset($jsonObj['data']) ) {

            try {
                DB::beginTransaction();

                DB::table('configurations')->truncate();
                foreach ($jsonObj['data'] as $obj) {
                    $obj['created_at'] = now();
                    $obj['updated_at'] = now();

                    DB::table('configurations')->insert($obj);

                }

                DB::commit();

            } catch ( Exception $e ) {
                DB::rollback();
                logger()->debug($e);
            }
        }

    	$this->command->info('> `configurations` Table Seeded!');
        $this->command->info('-------------------------------------');
    }
}
