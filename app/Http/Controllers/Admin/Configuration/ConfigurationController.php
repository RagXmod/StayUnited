<?php

namespace App\Http\Controllers\Admin\Configuration;

use Illuminate\Http\Request;
use Modules\Configuration\Eloquent\Repositories\ConfigurationRepositoryEloquent;
use Modules\Configuration\Http\Controllers\ConfigurationController as Controller;
use Cache;

class ConfigurationController extends Controller
{

    public function __construct(ConfigurationRepositoryEloquent $configurationModel)
    {
        $this->setRoutes( [
            'edit_page' => 'admin.configuration.detail'
        ]);
        parent::__construct( $configurationModel );
    }

    public function getIndex( $actionType = 'general')
    {

        $navigationArr = $this->navigations();
        $selectedActionArr = array_first(array_where($navigationArr, function( $item ) use($actionType) {
            return $item['identifier'] === $actionType;
        }));

        if ( !$selectedActionArr )
            return redirect()->route('admin.configuration.index','general');

        $configGroups = $this->configurationModel->findByGroup( $actionType );
        $data = [
            'action_type'     => $actionType,
            'configurations'  => $configGroups,
            'selected_action' => $selectedActionArr,
            'navigations'     => $navigationArr,
            'select_options'  => $this->selectOptions()
        ];

        if ( $actionType === 'general') {

            $_data = [
                'timezonelist'    => $this->getAllTimezones(),
                'countries'       => supportedCountries(),
                'languages'       => supportedLanguages()
            ];
            $data  = array_merge($data, $_data);
        }

        return view('admin.configuration.index', $data);
    }

    public function navigations() {

        $settings = [

            [
                'title' => 'General',
                'icon'  => 'tools'
            ],
            [
                'title' => 'Auth',
                'icon'  => 'user-cog'
            ],
            [
                'title' => 'Registration',
                'icon'  => 'user-plus'
            ],
            [
                'title' => 'Seo',
                'icon'  => 'search-dollar'
            ],
            [
                'title' => 'Analytics',
                'icon'  => 'chart-bar'
            ],
            [
                'title' => 'App',
                'icon'  => 'mobile-alt'
            ]
        ];


        foreach($settings as &$item) {
            $item['identifier'] = str_slug($item['title']);
            $item['link'] = route('admin.configuration.index', $item['identifier']);
        }

        return $settings;
    }

    public function selectOptions() {
        $options =  [
            [
                'label' => 'Yes',
                'selected' => true
            ],
            [
                'label' => 'No',
                'selected' => false
            ]
        ];

        foreach($options as &$opt) {
            $opt['value'] = str_slug($opt['label']);
        }

        return $options;
    }

}