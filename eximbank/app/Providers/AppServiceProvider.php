<?php

namespace App\Providers;

use App\Helpers\Tracking;
use App\Observers\OnlineActivityObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //$this->app->useStoragePath(config('app.datafile.dataroot'));
        \Illuminate\Database\Query\Builder::macro('toRawSql', function(){
            return array_reduce($this->getBindings(), function($sql, $binding){
                return preg_replace('/\?/', is_numeric($binding) ? $binding : "'".$binding."'" , $sql, 1);
            }, $this->toSql());
        });

        \Illuminate\Database\Eloquent\Builder::macro('toRawSql', function(){
            return ($this->getQuery()->toRawSql());
        });

        $this->app->singleton(
            \App\Repositories\District\DistrictRepositoryInterface::class,
            \App\Repositories\District\DistrictRepository::class
        );

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        //$this->app->bind(ItemsController::class, \App\Http\Controllers\Vendor\LaravelFilemanager\ItemsController::class);
    }

    public function boot()
    {
        \Schema::defaultStringLength(191);
        if(explode(':', config('app.url'))[0] == 'https') {
            $this->app['request']->server->set('HTTPS','on');
            \URL::forceScheme('https');
        }
        view()->composer('auth.login', 'App\Http\Composers\LoginComposer');
        view()->composer('layouts.top_menu', 'App\Http\Composers\TopmenuComposer');
        view()->composer('layouts.top_banner', 'App\Http\Composers\TopBannerComposer');
        view()->composer('layouts.menu_bottom', 'App\Http\Composers\MenuBottomComposer');
        view()->composer('layouts.backend.top_menu', 'App\Http\Composers\backend\TopmenuComposerBackend');
        view()->composer('layouts.left_menu', 'App\Http\Composers\LeftMenuFrontend');
        view()->composer(['layouts.app','react.layouts.app','layouts.backend'], 'App\Http\Composers\AppComposer');
//        view()->composer('*', function ($view) {
//            if (auth()->check())
//                $view->with('userUnits', User::getRoleAndManagerUnitUser());
//        });

        $modules = \Module::all();
        foreach ($modules as $module) {
            $this->loadMigrationsFrom([$module->getPath().'/Database/Migrations']);
        }
		 \Response::macro('attachment', function ($name, $content) {

            $headers = [
                'Content-type'        => 'text/plain',
                'Content-Disposition' => 'attachment; filename="'.$name.'"',
            ];

            return \Response::make($content, 200, $headers);

        });

        if (
            config('app.debug', false)
            && config('app.enable_logging', false)
        )
            DB::listen(function ($query) {
                $rawQuery = $query->sql;
                if (
                    is_array($query->bindings)
                    && count($query->bindings) > 0
                ) {
                    foreach ($query->bindings as $val) {
                        $rawQuery = preg_replace('[\?]', "'" . $val . "'", $rawQuery, 1);
                    }
                }

                Tracking::put((object)['sql' => $rawQuery, 'time' => $query->time], 'db', false);
        });
    }
}
