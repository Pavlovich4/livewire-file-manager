<?php

namespace Pavlovich4\LivewireFilemanager\Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;
use Pavlovich4\LivewireFilemanager\FileManagerServiceProvider;
use Livewire\LivewireServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Dotenv\Dotenv;

class TestCase extends Orchestra
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn(string $modelName) => 'Pavlovich4\\LivewireFilemanager\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );

        $this->setUpDatabase();

        $this->loadEnvironmentVariables();

        $this->getEnvironmentSetUp($this->app);

        Storage::fake('public');
    }

    public function setUpDatabase()
    {
        $mediaTableMigration = require __DIR__ . '/../vendor/spatie/laravel-medialibrary/database/migrations/create_media_table.php.stub';
        $folderTableMigration = require __DIR__ . '/../database/migrations/0001_01_01_000003_create_folders_table.php.stub';
        $fileTableMigration = require __DIR__ . '/../database/migrations/0001_01_01_000004_create_files_table.php.stub';

        (new $mediaTableMigration)->up();
        (new $folderTableMigration)->up();
        (new $fileTableMigration)->up();
    }

    protected function loadEnvironmentVariables()
    {
        if (! file_exists(__DIR__ . '/../.getenv')) {
            return;
        }

        $dotEnv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotEnv->load();
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

    protected function getEnvironmentSetUp($app)
    {
        config()->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
