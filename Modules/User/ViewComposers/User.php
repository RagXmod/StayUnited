<?php

namespace Modules\User\ViewComposers;

/**
 * Module User: Modules\User\ViewComposers\User
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

use Illuminate\View\View;
use Modules\User\Contracts\Authentication;

class User
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $auth = app(Authentication::class);

        $user = $auth->user();
        $view->with('user', $user ?? null);
    }
}