<?php

namespace App\Http\Controllers\Admin\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Http\Controllers\BaseController;

class SitemapController extends BaseController
{
    public function getIndex()
    {
        // change later not good calling controller..
        $indexControler = app(\App\Http\Controllers\Admin\Dashboard\IndexController::class);
        $storage = $this->__generateSitemapFolder();

        $files = $storage->allDirectories('sitemaps');

        $isSitemapIndexExists = $storage->exists('sitemap.xml');

        $sitemapArray = [];
        if(count($files) > 0)
        {
            foreach ($files as $key => $item) {

                list($index,$name) = explode('/', $item);
                if(!isset($sitemapArray[$name]))
                    $sitemapArray[$name] = [];

                if ( !$storage->exists($item.'.xml') )
                    continue;

                $sitemapArray[$name] = [
                    'url' => url($item.'.xml'),
                    'sitemaps' => []
                ];

                $subSitemaps = $storage->allFiles('sitemaps/'.$name);
                foreach ($subSitemaps as $key => $item) {
                    $sitemapArray[$name]['sitemaps'][] = url($item);
                    sort($sitemapArray[$name]['sitemaps'],SORT_NATURAL | SORT_FLAG_CASE);
                }
            }
        }

        $sitemapArray = array_filter($sitemapArray);


        $data = [
            'navigations'          => $indexControler->getNavigation(),
            'sitemapArray'         => $sitemapArray,
            'isSitemapIndexExists' => $isSitemapIndexExists,
        ];
        return view('admin.dashboard.sitemap', $data);
    }


    public function postGenerateSitemap(Request $request) {

        $request->validate([
            'sitemap_type' => 'required'
        ]);

        try {

            switch($request->get('sitemap_type')) {

                case 'apps':
                    $this->sitemapApps( $request->get('sitemap_type') );
                    break;
                case 'categories':
                    $this->sitemapCategory( $request->get('sitemap_type') );
                    break;
                case 'pages':
                    $this->sitemapPages( $request->get('sitemap_type') );
                    break;

                case 'all':
                    $this->sitemapAll( $request->get('sitemap_type') );
                    break;
            }

            return redirect()->route('admin.dashboard.sitemap-generator');

        } catch (Exception $e) {
            logger()->debug($e);
            return redirect()->route('admin.dashboard.sitemap-generator');
        }
    }

    public function sitemapAll( ) {

        $this->sitemapApps();
        $this->sitemapCategory();
        $this->sitemapPages();
    }

    public function sitemapApps( $name = 'apps' ) {

        $model       = app(\App\App\Eloquent\Entities\App::class);
        $collections = $model->cursor();

        $this->generateXML($collections, $name, 'app_detail_url');
    }


    public function sitemapCategory( $name = 'categories') {

        $model    = app(\App\App\Eloquent\Entities\Category::class);
        $collections = $model->cursor();

        $this->generateXML($collections, $name, 'page_url');
    }


    public function sitemapPages( $name  = 'pages') {

        $model    = app(\Modules\Page\Eloquent\Entities\Page::class);
        $collections = $model->cursor();

        $this->generateXML($collections, $name, 'page_url');
    }


    private function generateXML($collections, $name, $linkUrl = '#') {

        $sitemap = app("sitemap");

        $sitemap->setCache("dcm.sitemap-{$name}", 3600);

        // counters
        $counter        = 0;
        $sitemapCounter = 0;

        // remove apps files
        $storage = Storage::disk('public-path');
        $storage->delete($storage->files("sitemaps/{$name}"));
        $storage->delete("sitemaps/{$name}.xml");
        $images = [];
        foreach($collections as $index => $item) {

            $sitemapName    = "sitemaps/{$name}/{$name}-index-{$sitemapCounter}";
            if ($counter == 1000) {
                // generate new sitemap file
                $sitemap->store('xml',$sitemapName);
                // add the file to the sitemaps array
                $sitemap->addSitemap(url($sitemapName . '.xml'));
                // reset items array (clear memory)
                $sitemap->model->resetItems();
                // reset the counter
                $counter = 0;
                // count generated sitemap
                $sitemapCounter++;
            }

            if( isset($item->app_image_url))
                $images[] = [
                    'url'   => $item->app_image_url,
                    'title' => $item->title,
                    'caption' => str_limit( e($item->description) ,80)
                ];

            // change if needed
            $urlLink = $item[$linkUrl];

            // add product to items array
            $sitemap->add($urlLink, $item->updated_at->format('c'), '0.9', 'daily', $images, $item->title);
            // count number of elements
            $counter++;

        }

        $hasItems = $sitemap->model->getItems();
        // you need to check for unused items
        if (!empty($hasItems))
        {
            // generate sitemap with last items
            $sitemap->store('xml',$sitemapName);
            // add sitemap to sitemaps array
            $sitemap->addSitemap(url($sitemapName.'.xml'));
            // reset items array
            $sitemap->model->resetItems();
        }

        $sitemap->store('sitemapindex',"sitemaps/{$name}");
        $this->sitemapIndex();

    }

    /**
    * sitemapIndex()
    *
    *
    * @return void
    * @access  private
    */
    private function sitemapIndex()
    {
        // create sitemap index
        $sitemap = app("sitemap");

        $files = array_filter(Storage::disk('public-path')->files('sitemaps'), function ($file)
        {
            return preg_match('/(.*)\.xml$/U', $file);
        });

        if(count($files) > 0)
        {
            foreach ($files as $key => $item) {

                if(!str_contains($item,['sitemap.xml','-index-']))
                {
                    $time = Storage::disk('public-path')->lastModified($item);
                    $updated_at = now()->createFromTimestamp($time)->format('c');
                    $sitemap->addSitemap(url($item),$updated_at);
                }
            }
            // create file sitemap.xml in your public folder (format, filename)
            $sitemap->store('sitemapindex','sitemap');
        }
    }

    private function __generateSitemapFolder() {

        $storage = Storage::disk('public-path');
        if ( !$storage->exists('sitemaps'))
            $storage->makeDirectory('sitemaps');

        if ( !$storage->exists('sitemaps/apps'))
            $storage->makeDirectory('sitemaps/apps');

        if ( !$storage->exists('sitemaps/categories'))
            $storage->makeDirectory('sitemaps/categories');

        if ( !$storage->exists('sitemaps/pages'))
            $storage->makeDirectory('sitemaps/pages');
        return $storage;
    }

}
