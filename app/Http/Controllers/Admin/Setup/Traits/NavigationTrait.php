<?php

namespace App\Http\Controllers\Admin\Setup\Traits;

/**
 * Module Core Providers: App\Http\Controllers\Admin\Setup\Traits\NavigationTrait
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
*/


trait NavigationTrait {

    public function getNavigations() {
        return [
            [
                'title' => 'Menu',
                'link'  => route('admin.setup.menu.index'),
                'icon'  => 'sitemap'
            ],
            [
                'title' => 'Slider',
                'link'  => route('admin.setup.slider.index'),
                'icon'  => 'images'
            ],
            // [
            //     'title' => 'Sidebar',
            //     'link'  => route('admin.setup.sidebar.index'),
            //     'icon'  => 'list-ul'
            // ],
            [
                'title' => 'Ads Placement',
                'link'  => route('admin.setup.adsplacement.index'),
                'icon'  => 'money-bill-alt'
            ],
            [
                'title'       => 'Footer',
                'link'        => route('admin.setup.footer.index'),
                'icon'        => 'list-alt'
            ],

        ];
    }
}