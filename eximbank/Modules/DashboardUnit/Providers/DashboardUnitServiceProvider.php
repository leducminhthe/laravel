<?php

namespace Modules\DashboardUnit\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\DashboardUnit\Console\DashboardUnitCommand;
use Modules\DashboardUnit\Console\DashboardUnitCountOnlineCommand;
use Modules\DashboardUnit\Console\DashboardUnitCountOfflineCommand;
use Modules\DashboardUnit\Console\DashboardUnitCountQuizCommand;
use Modules\DashboardUnit\Console\DashboardUnitCountUserByOfflineCommand;
use Modules\DashboardUnit\Console\DashboardUnitCountUserByOnlineCommand;
use Modules\DashboardUnit\Console\DashboardUnitCourseByCourseEmployeeCommand;
use Modules\DashboardUnit\Console\DashboardUnitCourseByTrainingFormCommand;
use Modules\DashboardUnit\Console\DashboardUnitOfflineCourseCommand;
use Modules\DashboardUnit\Console\DashboardUnitOnlineCourseCommand;
use Modules\DashboardUnit\Console\DashboardUnitPartByQuizTypeCommand;
use Modules\DashboardUnit\Console\DashboardUnitQuizCommand;
use Modules\DashboardUnit\Console\DashboardUnitUserByCourseEmployeeCommand;
use Modules\DashboardUnit\Console\DashboardUnitUserByQuizTypeCommand;
use Modules\DashboardUnit\Console\DashboardUnitUserByTrainingFormCommand;

class DashboardUnitServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        $this->commands([
            DashboardUnitCommand::class,
            DashboardUnitCourseByTrainingFormCommand::class,
            DashboardUnitUserByTrainingFormCommand::class,
            DashboardUnitCourseByCourseEmployeeCommand::class,
            DashboardUnitUserByCourseEmployeeCommand::class,
            DashboardUnitPartByQuizTypeCommand::class,
            DashboardUnitUserByQuizTypeCommand::class,
            DashboardUnitCountOnlineCommand::class,
            DashboardUnitCountOfflineCommand::class,
            DashboardUnitCountQuizCommand::class,
            DashboardUnitCountUserByOnlineCommand::class,
            DashboardUnitCountUserByOfflineCommand::class,
            DashboardUnitOnlineCourseCommand::class,
            DashboardUnitOfflineCourseCommand::class,
            DashboardUnitQuizCommand::class,
        ]);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('dashboardunit.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'dashboardunit'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/dashboardunit');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_filter(array_merge(array_map(function ($path) {
            return $path . '/modules/dashboardunit';
        }, \Config::get('view.paths')), [$sourcePath]),function ($path) {
            return file_exists($path);
        }), 'dashboardunit');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/dashboardunit');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'dashboardunit');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'dashboardunit');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
