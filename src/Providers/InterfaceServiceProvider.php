<?php

namespace NGiraud\Repositories\Providers;

use Illuminate\Support\ServiceProvider;

class InterfaceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /*$this->publishes([
			__DIR__.'../'	=> base_path('app'),
		]);*/
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('NGiraud\Repositories\Interfaces\RepositoryInterface', 'NGiraud\Repositories\Repositories\Repository');
    }
}
