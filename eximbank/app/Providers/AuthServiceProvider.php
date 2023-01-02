<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Helpers\Tracking;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return in_array($user->username, ['admin', 'superadmin']) ? true : null;
        });

        Gate::define('viewWebSocketsDashboard', function ($user = null) {
            return in_array($user->username, ['admin', 'superadmin']) ? true : null;
        });
    }

    private function trackingEvents()
    {
        // app events depend on the eloquent trigger
        Event::listen('eloquent.*', function ($eventName) {
            Tracking::put((object) ['event' => $eventName], 'app');
        });

        // layout init events
        Event::listen('creating:*', function ($eventName) {
            Tracking::put((object) ['event' => $eventName], 'lay', false);
        });

        // layout compose events
        Event::listen('composing:*', function ($eventName) {
            Tracking::put((object) ['event' => $eventName], 'lay', false);
        });

        // all events tracking trail
        Event::listen([
            '*.*',
            'creating:*',
            'composing:*',
            'bootstrapped:*'
        ], function ($eventName) {
            Tracking::put((object) ['event' => $eventName], 'event', false);
        });
    }
}
