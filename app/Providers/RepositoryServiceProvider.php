<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind("App\Repository\Interfaces\CrashInterface", "App\Repository\Queries\CrashRepo");
        $this->app->bind("App\Repository\Interfaces\UserInterface", "App\Repository\Queries\UserRepo");
        $this->app->bind("App\Repository\Interfaces\RoleInterface", "App\Repository\Queries\RoleRepo");
        $this->app->bind("App\Repository\Interfaces\PlansInterface", "App\Repository\Queries\PlansRepo");
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
