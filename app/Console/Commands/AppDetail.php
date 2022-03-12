<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Facades\App\App\Facades\ApiFacade;

use Storage;
use Exception;

class AppDetail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app-detail:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get details for all apps with cron check is set to true.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {

            // DB::beginTransaction();
            $appModel = app(\App\App\Eloquent\Entities\App::class);
            $apps     = $appModel->where('is_cron_check', 1)->cursor();

            $storage = Storage::disk('public-path');

            foreach($apps as $index => $app) {
                echo 'starting app = '.$app->app_id;
                try {

                    if ( !$storage->exists('apk-json'))
                        $storage->makeDirectory('apk-json');

                    //
                    $jsonFilePath = "apk-json/{$app->app_id}.json";
                    if ( $storage->exists($jsonFilePath)) {
                        $details = json_decode($storage->get($jsonFilePath), true);
                    } else {
                        $details = ApiFacade::detail($app->app_id);
                        $storage->put("apk-json/{$app->app_id}.json", json_encode($details));
                    }

                    // safe to file..
                } catch (Exception $e) {
                    $details = [];
                    // 2 means.. cannot read or 404 not found.
                    $app->is_cron_check = 2;
                    $app->save();
                    logger()->notice("Problem in getting app detail: {$app->app_id}");
                    // logger()->debug( $th);
                    continue;
                }

                if ( $details ) {
                    $appResource = app(\App\Http\Controllers\Admin\App\Resources\AppResource::class);

                    $details['seo_keyword']   = commaStringToArrayKeywords($details['seo_keyword']);

                    $request = request();
                    $request->replace($details);
                    $appResource->update(request(), $app->id);

                    logger()->debug(print_r($details,true));
                    $app->is_cron_check = 0;
                    $app->save();
                }
                echo "APPID: {$app->app_id} \n";


                @ob_flush();
                flush();
                sleep(3);
            }

            // DB::commit();

        } catch (Exception $e) {
            logger()->debug('Reset Database Problem. '.print_r($e->getMessage(),true));
            // DB::rollback();
        }

    }
}
