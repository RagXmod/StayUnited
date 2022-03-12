<?php

use Illuminate\Database\Seeder;

class HomePageAdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // ignore foreign
            $this->_advertisement();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // set foreign
    }

    private function _advertisement() {

        $advertisementBlockArr = [
            [
                "title"    => "Homepage Leaderboard",
                "ads_code" => 'Place your ads here'
            ],
            [
                "title"    => "Sidebar",
                "ads_code" => 'Place your ads here'
            ],
            [
                "title"    => "App Detail Page Top Leaderboard",
                "ads_code" => 'Place your ads here'
            ],
            [
                "title"    => "App Detail Page Bottom Leaderboard",
                "ads_code" => 'Place your ads here'
            ],

        ];
        try {

            DB::beginTransaction();

                DB::table('advertisements')->truncate();
                DB::table('home_ads_placement_blocks')->truncate();

                foreach ($advertisementBlockArr as $index => $item) {

                    $item['created_at'] = $item['updated_at'] = now();
                    $item['identifier'] = str_slug($item['title']);

                    DB::table('advertisements')->insert($item);
                }

                foreach ($advertisementBlockArr as $index => $item) {
                    $item['created_at'] = $item['updated_at'] = now();
                    $item['identifier'] = str_slug($item['title']);

                    unset($item['ads_code']);
                    DB::table('home_ads_placement_blocks')->insert($item);
                }


            DB::commit();

        } catch ( Exception $e ) {

            logger()->debug($e);
            DB::rollback();
        }
    }
}
