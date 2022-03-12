<?php

/**
 * Module Core: Modules\Core\Http\Controllers\BaseController
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

namespace Modules\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Modules\User\Contracts\Authentication;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BaseController extends Controller
{

    use ValidatesRequests;

    public $locale;

    public function __construct()
    {
        $this->locale = App::getLocale();
        $this->auth = app(Authentication::class);
    }
}
