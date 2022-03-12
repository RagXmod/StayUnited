<?php

use Illuminate\Database\Seeder;

class HomePageMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // ignore foreign
            $this->_homePageMenu();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // set foreign
    }

    private function _homePageMenu() {

        $menuArr = [
            [
                "href"   =>  '/',
                "icon"   => "fas fa-home",
                "target" => "_top",
                "text"   => "Home",
                "title"  => "My Home",
            ],
            [
                "href"   =>  route('web.category.detail', str_slug('Apps')),
                "icon"   => "fab fa-android",
                "target" => "_top",
                "text"   => "Apps",
                "title"  => "My Apps",
            ],
            [
                "href"   =>   route('web.category.detail', str_slug('Games')),
                "icon"   => "fas fa-gamepad",
                "target" => "_top",
                "text"   => "Games",
                "title"  => "My Games",
            ]
        ];
        try {

            DB::beginTransaction();

                DB::table('home_page_menus')->truncate();
                foreach ($menuArr as $index => $menu) {

                    $menu['created_at'] = $menu['updated_at'] = now();
                    $menu['position'] = ++$index;

                    DB::table('home_page_menus')->insert($menu);
                }

            DB::commit();

        } catch ( Exception $e ) {

            logger()->debug($e);
            DB::rollback();
        }
    }
}
