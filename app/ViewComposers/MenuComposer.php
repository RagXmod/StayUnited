<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use App\App\Eloquent\Repositories\HomePageMenuRepositoryEloquent;

class MenuComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if ( $view->getName() === 'common.navigation') {

            $homePageModel = app(HomePageMenuRepositoryEloquent::class);

            $menuArr = $homePageModel->menus();
            $view->with( 'menuArr', $menuArr);
        }
    }


}