<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use Facades\App\App\Facades\AppFacade;

class SidebarComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        if ( $view->getName() === 'web.sidebar.sidebar') {

            $sidebarArr = AppFacade::getSideBarApps();
            $view->with( 'sidebar', $sidebarArr);
        }
    }


}
