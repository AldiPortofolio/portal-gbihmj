<?php

namespace digipos\Http\Middleware;

use Closure;
use Request;
use digipos\Libraries\Alert;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null){
        if (Auth()->guard($guard)->guest()) {
            if($guard == 'admin'){
                if ($request->ajax()) {
                    return response('Unauthorized.', 401);
                } else {
                    $url = Request::url();
                    Alert::fail('Please login to proceed');
                    return redirect()->guest('_admin/login');
                }
            }else if($guard == 'web'){
                if ($request->ajax()) {
                    return response('Unauthorized.', 401);
                } else {
                    $url = Request::url();
                    Alert::fail('Please login to proceed');
                    return redirect()->guest('/');
                }
            }
        }

        return $next($request);
    }
}
