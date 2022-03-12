<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use App\App\Eloquent\Repositories\HomePageFooterRepositoryEloquent;

class FooterComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        if ( $view->getName() == 'common.footer') {

            $model = app(HomePageFooterRepositoryEloquent::class);
            $footerMenuArr = $model->menus();
            $view->with('footerMenuArr', $footerMenuArr);
        }
    }
}