<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\App\Eloquent\Entities\AppFeaturedPost;
use Facades\App\Facades\ApiFacade;


class DemoDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // ignore foreign
            $this->demoApps();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // set foreign
    }

    private function demoApps() {

        DB::table('app_featured_posts')->truncate();

        $featuredPosts = [
            [
                'title' => 'Recommended for you',
                'apps'  => [
                    'com.facebook.katana',
                    'com.facebook.orca',
                    'com.instagram.android',
                    'com.google.android.youtube',
                    'com.game.JewelsStar',
                    'com.application.zomato'
                ]
            ],
            [
                'title' => 'Editors\' Choice Games',
                'apps'  => [
                    'com.mobile.legends',
                    'com.king.candycrushsaga',
                    'com.moonactive.coinmaster',
                    'com.ludusstudio.runandgun',
                    'com.ea.gp.nbamobile',
                    'com.combineinc.streetracing.driftthreeD'
                ]
            ],
            [
                'title' => 'Editors\' Choice Apps',
                'apps'  => [
                    'com.facebook.katana',
                    'com.mobile.legends',
                    'com.gameloft.android.ANMP.GloftFWHM',
                    'com.netflix.mediaclient',
                    'com.valvesoftware.android.steam.community',
                    'com.gamefly.android.gamecenter'
                ]
            ],
            [
                'title' => 'Top Selling Paid Apps',
                'apps'  => [
                    'com.mojang.minecraftpe',
                    'com.t2ksports.nba2k19and',
                    'com.yy.hiyo',
                    'com.utorrent.client',
                    'com.maxmpz.audioplayer.unlock',
                    'com.tencent.ig'

                ]
            ],
            [
                'title' => 'Rising stars in Social Media',
                'apps'  => [
                    'com.facebook.katana',
                    'com.instagram.android',
                    'com.snapchat.android',
                    'com.pinterest',
                    'com.sgiggle.production',
                    'com.oovoo'
                ]
            ],
            [
                'title' => 'Most downloaded in Music Player',
                'apps'  => [
                    'com.spotify.music',
                    'com.maxmpz.audioplayer',
                    'com.aimp.player',
                    'com.soundcloud.android',
                    'com.musixmatch.android.lyrify',
                    'com.slacker.radio'
                ]
            ],
            [
                'title' => 'Hottest in Fitness App right now',
                'apps'  => [

                    'com.runtastic.android',
                    'losebellyfat.flatstomach.absworkout.fatburning',
                    'sworkitapp.sworkit.com',
                    'homeworkout.homeworkouts.noequipment',
                    'com.fitnesskeeper.runkeeper.pro',
                    'com.nike.ntc'
                ]

            ],
            [
                'title' => 'Rising stars in Tools',
                'apps'  => [


                    'com.cleanmaster.mguard',
                    'com.sail.advanced.booster',
                    'com.google.android.apps.translate',
                    'com.sec.android.easyMover',
                    'de.mobileconcepts.cyberghost',
                    'com.dewmobile.kuaiya.play'
                ]
            ],
            [
                'title' => 'Most downloaded in Action',
                'apps'  => [

                    'com.lx.HeroesOfAtlan',
                    'com.madfingergames.deadzone',
                    'com.glu.modwarsniper',
                    'com.glu.flcn_new',
                    'com.jiinfeng3d.bfrdemo',
                    'com.gamehivecorp.beattheboss3'
                ]
            ]
        ];
        try {


                $featuredPostModel = app(AppFeaturedPost::class);


                foreach($featuredPosts as $post) {

                    $title = $post['title'];
                    $slugs = str_slug($title);
                    $data = [
                        'title'             => $title,
                        'status_identifier' => 'active',
                        'slug'              => $slugs,
                        'description'       => $title,
                        'seo_title'         => $title,
                        'seo_keyword'       => str_replace('-', ',', $slugs),
                        'seo_description'   => $title,
                        'icon'              => '',
                    ];
                    $featuredModel = $featuredPostModel->updateOrCreate(['slug' => $slugs], $data);


                    if ( isset($post['apps'])) {


                        $appModel = app(\App\App\Eloquent\Entities\App::class);

                        $arrayAppIds = [];
                        foreach($post['apps'] as $app) {

                            $data = [
                                'user_id'       => 1,
                                'is_cron_check' => 1,
                                'app_id'        => $app,
                            ];
                            $modelApp  = $appModel->updateOrCreate(['app_id' => $app], $data);

                            $arrayAppIds[] = $modelApp->id;
                        }

                        // $arrayAppIds = array_pluck($apps, ['id']);
                        $appIds      = [];
                        $positionIds = [];
                        foreach ($arrayAppIds as $index => $id) {
                            $appIds     [] = $id;
                            $positionIds[] = ['position' => ++$index, 'user_id' => 1];
                        }
                        $collectArray = array_combine($appIds, $positionIds);
                        $featuredModel->apps()->sync( $collectArray );
                        // return true;


                    }
                }

        } catch ( Exception $e ) {

            logger()->debug($e);
        }
    }
}
