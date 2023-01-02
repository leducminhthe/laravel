<?php

namespace App\Http\Middleware;

use Closure;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_id = @profile()->user_id;
        if (session()->has('locale_'.$user_id)) {
            \App::setLocale(session()->get('locale_'.$user_id));
        }
        return $next($request);
    }
}
