<?php

namespace App\Http\Middleware;

use Closure;
use Modules\User\Entities\ProfileChangedPass;
use TorMorten\Eventy\Facades\Events;
use Illuminate\Http\Request;
class AuthorizeTeacher
{
    public function handle(Request $request, Closure $next)
    {
        if (\Auth::check() && \Auth::user()->isTeacher()) {
            return $next($request);
        }
        return redirect('/');
    }
}
