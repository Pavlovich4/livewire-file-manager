<?php

namespace Pavlovich4\LivewireFilemanager;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class FileManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-filemanager');

        $this->registerBladeComponents()
            ->registerBladeDirectives()
            ->registerLivewireComponents();

        // Publishing is only necessary when using the CLI
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        // Register translations
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');
    }

    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'livewire-filemanager');

        // Bind the custom path generator
        // $this->app->bind(PathGenerator::class, CustomPathGenerator::class);
    }

    protected function bootForConsole()
    {
        // Publishing the configuration file
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('livewire-filemanager.php'),
        ], 'livewire-filemanager-config');

        // Publishing the views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/livewire-filemanager'),
        ], 'livewire-filemanager-views');

        // Publishing migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'livewire-filemanager-migrations');
    }

    protected function registerBladeComponents()
    {
        Blade::component('livewire-filemanager::components.folder-tree', 'filemanager-folder-tree');
        Blade::component('livewire-filemanager::components.file-icon', 'filemanager-icon');
        Blade::component('livewire-filemanager::components.image-loupe', 'filemanager-image-loupe');
        Blade::component('livewire-filemanager::components.loading-spinner', 'filemanager-loading-spinner');

        return $this;
    }

    public function registerBladeDirectives()
    {
        Blade::directive('livewireFileManagerScript', function () {
            return "<?php echo view('livewire-filemanager::scripts'); ?>";
        });

        Blade::directive('livewireFileManagerStyle', function () {
            return "<?php echo view('livewire-filemanager::styles'); ?>";
        });

        return $this;
    }

    protected function registerLivewireComponents()
    {
        // Register Livewire components
        Livewire::component('file-manager', \Pavlovich4\LivewireFilemanager\Livewire\FileManager::class);

        return $this;
    }
}
