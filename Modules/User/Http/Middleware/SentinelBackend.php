<?php

/**
 * Module User: Modules\User\Http\Middleware\SentinelBackend
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

namespace Modules\User\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Core\Traits\ResponseTrait;

class SentinelBackend
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        try {

            $defaultLoginRoutes = config('dcm.auth.default_login_routes', 'web.user.index');
            if( config('dcm.auth.admin.default-login-routes') ) {
                $defaultLoginRoutes = config('dcm.auth.admin.default-login-routes');
            }

            $auth = app(\Modules\User\Contracts\Authentication::class);
            $user = $auth->user();

            if ($user) {
                if ( $user->hasAccess('user.can_login_in_admin') )
                    return $next($request);
            }

            $url = sprintf('%s?return-url=%s', route($defaultLoginRoutes) , $request->url());
            return redirect($url)->withError(['message' => 'You dont have enough permission to access for this page. Please contact our webmaster.']);

        } catch (Exception $e) {

            // log error message
            logger()->error($e->getMessage());

            // redirect to homepage..
            return redirect('/');
        }
    }
}
