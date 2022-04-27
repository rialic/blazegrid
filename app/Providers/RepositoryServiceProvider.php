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
        $path = app_path('Repository/Interfaces');
        $files = scandir($path);

        collect($files)->each(function($file, $key) {
            $isNotHiddenFile = $file !== '.' && $file !== '..';

            if ($isNotHiddenFile) {
                $fileName = substr($file, 0, strpos($file, 'Interface'));

                $this->app->bind("App\Repository\Interfaces\{$fileName}Interface", "App\Repository\Queries\{$fileName}Repo");
            }
        });
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
