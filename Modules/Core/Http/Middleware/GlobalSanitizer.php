<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use \Illuminate\Http\Request;
use Facades\Modules\Core\Support\Input\Sanitizer;

class GlobalSanitizer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $_inputs = $request->all();

        // adjust if needed
        $inputs =  Sanitizer::array_strip_tags($_inputs);
        $request->replace($inputs);

        return $next($request);
    }
}
