<?php

/**
 * Module Installation: Modules\Installation\Http\Middleware\VerifyInstallation
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

namespace Modules\Installation\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VerifyInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {
        if (! file_exists(base_path('.env')) && ! $request->is('dcm/install*')) {
            return redirect()->to('dcm/install');
        }

        if (file_exists(base_path('.env')) && $request->is('install*') && ! $request->is('install/complete')) {
            throw new NotFoundHttpException;
        }

        return $next($request);
    }
}
