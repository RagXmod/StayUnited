<?php


Route::get('generate-sitemap', function()
{
    // // create sitemap
    $sitemap = app("sitemap");

    // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
    //  by default cache is disabled
    $sitemap->setCache('dcm.sitemap', 60);

    // if (!$sitemap->isCached()) {

        $sitemap->add(URL::to('/'), '2012-08-25T20:10:00+02:00', '1.0', 'daily');
        $sitemap->add(URL::to('pages'), '2012-08-26T12:30:00+02:00', '0.9', 'monthly');


        $pageModel = app(\Modules\Page\Eloquent\Repositories\PageRepositoryEloquent::class);
        $pages = $pageModel->getAllPages();
        if ( $pages ) {
            foreach($pages as $page) {
                $sitemap->add( $page['link'] ?? url('/'), now()->format('c'), '0.9', 'monthly');
            }
        }

        $appModel       = app(\App\App\Eloquent\Repositories\AppRepositoryEloquent::class);
        $appCollections = $appModel->makeModel()->cursor();

        // counters
        $counter = 0;
        $sitemapCounter = 0;

        foreach($appCollections as $app) {

            if ($counter == 50000) {
                // generate new sitemap file
                $sitemap->store('xml', 'sitemap-' . $sitemapCounter);
                // add the file to the sitemaps array
                $sitemap->addSitemap(secure_url('sitemap-' . $sitemapCounter . '.xml'));
                // reset items array (clear memory)
                $sitemap->model->resetItems();
                // reset the counter
                $counter = 0;
                // count generated sitemap
                $sitemapCounter++;
            }

            // add product to items array
            $sitemap->add($app->app_detail_url, $app->updated_at->format('c'), '0.9', 'daily');
            // count number of elements
            $counter++;

        }


    // }

    // you need to check for unused items
	if (!empty($sitemap->model->getItems())) {
		// generate sitemap with last items
		$sitemap->store('xml', 'sitemap-' . $sitemapCounter);
		// add sitemap to sitemaps array
		$sitemap->addSitemap(secure_url('sitemap-' . $sitemapCounter . '.xml'));
		// reset items array
		$sitemap->model->resetItems();
	}

	// generate new sitemapindex that will contain all generated sitemaps above
	$sitemap->store('sitemapindex', 'sitemap');


    $sitemap->store('xml', 'sitemap');
    // return $sitemap->render('xml');
    exit;
});

