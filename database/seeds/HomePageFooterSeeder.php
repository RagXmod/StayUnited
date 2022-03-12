<?php

use Illuminate\Database\Seeder;
use App\App\Eloquent\Entities\HomePageFooter;

class HomePageFooterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // ignore foreign
            $this->_homePageFooter();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // set foreign
    }

    private function _homePageFooter() {



        $menuArr = [

            [
                "href"   =>  '#',
                "icon"   => "fa fa-address-card",
                "target" => "_top",
                "text"   => "Who we are",
                "title"  => "Who we are",
                "children" => [
                    [
                        "href"   => route('web.page.detail', str_slug('About Us')),
                        "icon"   => "fa fa-address-card",
                        "target" => "_top",
                        "text"   => "About Us",
                        "title"  => "About Us",
                    ],
                    [
                        "href"   =>  route('web.page.detail', str_slug('Privacy Policy')),
                        "icon"   => "fa fa-user-secret",
                        "target" => "_top",
                        "text"   => "Privacy Policy",
                        "title"  => "Privacy Policy",
                    ],
                    [
                        "href"   =>  route('web.page.detail', str_slug('DMCA Disclaimer')),
                        "icon"   => "fa fa-balance-scale",
                        "target" => "_top",
                        "text"   => "DMCA Disclaimer",
                        "title"  => "DMCA Disclaimer",
                    ],
                    [
                        "href"   =>  route('web.page.detail', str_slug('Terms of Use')),
                        "icon"   => "fa fa-user-shield",
                        "target" => "_top",
                        "text"   => "Terms of Use",
                        "title"  => "Terms of Use",
                    ]
                ]
            ],
            [
                "href"   =>  '#',
                "icon"   => "fa fa-address-card",
                "target" => "_top",
                "text"   => "Connect with us",
                "title"  => "Connect with us",
                "children" => [
                    [
                        "href"   =>  route('web.home.contactus'),
                        "icon"   => "fa fa-headset",
                        "target" => "_top",
                        "text"   => "Contact Us",
                        "title"  => "Contact Us",
                    ],
                    [
                        "href"   =>  route('web.home.reportcontent'),
                        "icon"   => "fa fa-flag-checkered",
                        "target" => "_top",
                        "text"   => "Report Abuse Content",
                        "title"  => "Report Abuse Content",
                    ],
                    [
                        "href"   =>  '/',
                        "icon"   => "fa fa-rss-square",
                        "target" => "_top",
                        "text"   => "RSS Feeds",
                        "title"  => "RSS Feeds",
                    ],
                    [
                        "href"   =>  url('sitemap.xml'),
                        "icon"   => "fa fa-sitemap",
                        "target" => "_top",
                        "text"   => "Sitemap",
                        "title"  => "Sitemap",
                    ]
                ]
            ],
            [
                "href"   =>  '#',
                "icon"   => "fa fa-address-card",
                "target" => "_top",
                "text"   => "Follow Us",
                "title"  => "Follow Us",
                "children" => [
                    [
                        "href"   =>  dcmConfig('social_facebook'),
                        "icon"   => "fab fa-facebook-square",
                        "target" => "_top",
                        "text"   => "Facebook",
                        "title"  => "Facebook",
                    ],
                    [
                        "href"   =>  dcmConfig('social_twitter'),
                        "icon"   => "fab fa-twitter-square",
                        "target" => "_top",
                        "text"   => "Twitter",
                        "title"  => "Twitter",
                    ],
                    [
                        "href"   =>  dcmConfig('social_google_plus'),
                        "icon"   => "fab fa-google-plus-square",
                        "target" => "_top",
                        "text"   => "Google+",
                        "title"  => "Google+",
                    ],
                    [
                        "href"   =>  dcmConfig('social_pinterest'),
                        "icon"   => "fab fa-pinterest",
                        "target" => "_top",
                        "text"   => "Pinterest",
                        "title"  => "Pinterest",
                    ]
                ]
            ],

        ];
        try {



            DB::beginTransaction();

                DB::table('home_page_footers')->truncate();
                foreach ($menuArr as $index => $menu) {
                    $menu['created_at'] = $menu['updated_at'] = now();
                    HomePageFooter::create($menu);
                }

            DB::commit();

        } catch ( Exception $e ) {
            logger()->debug($e);
            DB::rollback();
        }
    }

}
