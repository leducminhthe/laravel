<?php

namespace App\Providers;

use App\Repositories\Contracts\{
    AbstractRepositoryInterface,
	ProfileRepositoryInterface,
	ProfileServiceInterface,
	UnitRepositoryInterface,
	UserRepositoryInterface,
};
use App\Repositories\Eloquent\{
    AbstractRepository,
	ProfileRepository,
	ProfileService,
	UnitRepository,
	UserRepository,
};

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any Repository Patters.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
			AbstractRepositoryInterface::class,
			AbstractRepository::class
		);

		$this->app->bind(
			ProfileRepositoryInterface::class,
			ProfileRepository::class
		);

		$this->app->bind(
			ProfileServiceInterface::class,
			ProfileService::class
		);

		$this->app->bind(
			UnitRepositoryInterface::class,
			UnitRepository::class
		);

		$this->app->bind(
			UserRepositoryInterface::class,
			UserRepository::class
		);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
