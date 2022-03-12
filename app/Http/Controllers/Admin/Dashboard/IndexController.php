<?php

namespace App\Http\Controllers\Admin\Dashboard;

use Facades\App\App\Facades\AppFacade;
use Illuminate\Http\Request;
use Facades\Modules\User\Facades\UserFacade;
use CyrildeWit\EloquentViewable\Support\Period;
use Modules\Core\Http\Controllers\BaseController;

class IndexController extends BaseController
{
    public function getIndex()
    {

        $appModel = app(\App\App\Eloquent\Entities\App::class);
        $item = $appModel->orderByUniqueViews('desc', Period::pastDays(3))->take(6)->get();

        $data = [
            'navigations'      => $this->getNavigation(),
            'dashboard'        => UserFacade::dashboardInfo(),
            'most_viewed_apps' => AppFacade::mostViewedApps(5),
            'categories'       => AppFacade::categories(5),
        ];
        // dd($data);
        return view('admin.dashboard.index', $data);
    }

    public function getNavigation() {

        return [
            [
                'title' => 'Create New App',
                'link' => route('admin.app.create'),
            ],
            [
                'title' => 'Generate Sitemap',
                'link' => route('admin.dashboard.sitemap-generator') ,
            ],
            // [
            //     'title' => 'Submitted Apps',
            //     'link' => '#',
            // ],
            [
                'title' => 'Clear & Cache Config',
                'link' => route('artisan.command', 'config-clear_config-cache'),
            ],
            [
                'title' => 'Clear & Cache Routes',
                'link' => route('artisan.command', 'route-clear_route-cache'),
            ],
            [
                'title' => 'Clear All Cache',
                'link' => route('artisan.command', 'cache-clear_view-clear'),
            ],
            [
                'title' => 'View System Logs',
                'link' =>  route('artisan.logviewer.list'),
            ]
        ];
    }
}
