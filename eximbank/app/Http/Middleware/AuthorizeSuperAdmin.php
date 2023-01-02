<?php

namespace App\Http\Middleware;

use Closure;
use Modules\User\Entities\ProfileChangedPass;
use TorMorten\Eventy\Facades\Events;
use Illuminate\Http\Request;
use App\Models\Permission;

class AuthorizeSuperAdmin 
{
    public function handle(Request $request, Closure $next)
    {
        if (\Auth::check() && Permission::isSuperAdmin()) {
            return $next($request);
        }
        return redirect('/');
    }
}
