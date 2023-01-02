<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Cache;

class AutoLogout
{
    protected $timeout = 1800; //15 minutes
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        $routerGroup = $request->route()->getPrefix()=='/game';
        if ($request->route()->getPrefix()=='/game')
            return $next($request);
        if (!Session::has('lastActivityTime')) {
            Session::put('lastActivityTime', time());
        } elseif (time() - Session::get('lastActivityTime') > $this->getTimeOut()) {
            Session::forget('lastActivityTime');
            \Auth::logout();
            return redirect(route('login'))->withErrors(['You had not activity in 15 minutes']);
        }
        Session::put('lastActivityTime', time());//f5 browser
        return $next($request);
    }
    protected function getTimeOut()
    {
        return (env('SESSION_LIFETIME')*60) ?: $this->timeout;
    }
}
