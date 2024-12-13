<?php

/*
|--------------------------------------------------------------------------
| Livewire File Manager Configuration
|--------------------------------------------------------------------------
|
| This configuration file lets you customize the behavior of the Livewire
| File Manager package. You can specify the storage disk, configure media
| library settings, and adjust other features to match your needs.
*/

return [
    'disk' => 'public',
    'media' => [
        'path_generator' => Pavlovich4\LivewireFilemanager\Support\CustomPathGenerator::class,
    ],
];
