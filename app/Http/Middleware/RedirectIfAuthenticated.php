<?php

namespace digipos\Http\Middleware;

use Closure;
use digipos\Libraries\Alert;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
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
        if (auth()->guard($guard)->check()) {
            if($guard == 'admin'){
                Alert::fail('You already login');
                return redirect('_admin');
            }else if($guard == 'web'){
                Alert::fail('You already login');
                return redirect('/');
            }
        }

        return $next($request);
    }
}
