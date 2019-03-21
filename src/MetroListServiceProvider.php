<?php

namespace Grechanyuk\MetroList;

use Illuminate\Support\ServiceProvider;

class MetroListServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'grechanyuk');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'grechanyuk');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/metrolist.php', 'metrolist');

        // Register the service the package provides.
        $this->app->singleton('metrolist', function ($app) {
            return new MetroList;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['metrolist'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/metrolist.php' => config_path('metrolist.php'),
        ], 'metrolist.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/grechanyuk'),
        ], 'metrolist.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/grechanyuk'),
        ], 'metrolist.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/grechanyuk'),
        ], 'metrolist.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
