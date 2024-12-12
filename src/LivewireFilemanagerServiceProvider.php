<?php

namespace Pavlovich4\LivewireFilemanager;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireFilemanagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-filemanager');

        // Register Livewire components
        Livewire::component('file-manager', \Pavlovich4\LivewireFilemanager\Components\FileManager::class);
        Livewire::component('file-dropzone', \Pavlovich4\LivewireFilemanager\Components\FileDropzone::class);

        if ($this->app->runningInConsole()) {
            // Publish views
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/livewire-filemanager'),
            ], 'views');

            // Publish config
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('livewire-filemanager.php'),
            ], 'config');

            // Publish migrations
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'migrations');

            // Publish assets
            $this->publishes([
                __DIR__.'/../resources/css' => public_path('vendor/livewire-filemanager'),
            ], 'assets');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'livewire-filemanager');

        // Register the main class to use with the facade
        $this->app->singleton('livewire-filemanager', function () {
            return new LivewireFilemanager;
        });
    }
}
