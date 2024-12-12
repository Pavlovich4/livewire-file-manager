<?php

namespace Pavlovich4\LivewireFilemanager\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Pavlovich4\LivewireFilemanager\FileManagerServiceProvider;
use Livewire\LivewireServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Pavlovich4\\LivewireFilemanager\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        Storage::fake('public');
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            MediaLibraryServiceProvider::class,
            FileManagerServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
