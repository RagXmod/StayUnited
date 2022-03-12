<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Sitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:auto-generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto generate sitemap';

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

            $sitemap = app(\App\Http\Controllers\Admin\Dashboard\SitemapController::class);
            $sitemap->sitemapAll();
            \Artisan::call('cache:clear');

        } catch (Exception $e) {
            logger()->debug('Reset Database Problem. '.print_r($e->getMessage(),true));
        }

    }
}
