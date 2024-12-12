<?php

namespace Pavlovich4\LivewireFilemanager;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Pavlovich4\LivewireFilemanager\Support\CustomPathGenerator;

class FileManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'livewire-filemanager');

        // Register Blade components
        Blade::component('livewire-filemanager::components.file-manager', 'filemanager');
        Blade::component('livewire-filemanager::components.folder-tree', 'filemanager-folder-tree');
        Blade::component('livewire-filemanager::components.file-preview', 'filemanager-preview');
        Blade::component('livewire-filemanager::components.file-icon', 'filemanager-icon');
        Blade::component('livewire-filemanager::components.scripts', 'filemanager-scripts');

        // Publishing is only necessary when using the CLI
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'livewire-filemanager');

        // Bind the custom path generator
        $this->app->bind(PathGenerator::class, CustomPathGenerator::class);
    }

    protected function bootForConsole()
    {
        // Publishing the configuration file
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('livewire-filemanager.php'),
        ], 'livewire-filemanager-config');

        // Publishing the views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/livewire-filemanager'),
        ], 'livewire-filemanager-views');

        // Publishing migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'livewire-filemanager-migrations');
    }
}
