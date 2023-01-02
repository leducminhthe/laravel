<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Tracking;

class InspectMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        config('app.debug', false)
            && config('app.enable_logging', false)
            && $this->dumpInspector();

        return $response;
    }

    private function dumpInspector()
    {
        $dumpScopes = ['db', 'backtrace', 'app', 'lay', 'event'];
        $dumpSlowScopes = ['db', 'app', 'lay', 'event'];

        // $dumpScopes = ['db', 'backtrace'];
        // $dumpSlowScopes = ['db'];

        foreach ($dumpScopes as $scope) {
            Tracking::dump($scope, !in_array($scope, $dumpSlowScopes));
            in_array($scope, $dumpSlowScopes) && Tracking::dumpSlow($scope);
        }
    }
}
