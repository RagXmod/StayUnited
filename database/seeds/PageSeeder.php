<?php

use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
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
        $jsonObj  = $storage->get($jsonPath.'default-pages.json');
        $jsonObj= preg_replace('/\s+/', ' ',$jsonObj);
        $jsonObj  = json_decode($jsonObj, true);

        if ( $jsonObj && isset($jsonObj['data']) ) {

            try {


                DB::table('pages')->truncate();
                foreach ($jsonObj['data'] as $obj) {
                    $obj['identifier'] = str_slug($obj['slug'],'_');
                    $obj['created_at'] = now();
                    $obj['updated_at'] = now();

                    DB::table('pages')->insert($obj);

                }



            } catch ( Exception $e ) {

                logger()->debug($e);
            }
        }

    	$this->command->info('> `pages` Table Seeded!');
        $this->command->info('-------------------------------------');
    }
}
