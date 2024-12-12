<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'disk' => 'public',
    'media' => [
        'path_generator' => Pavlovich4\LivewireFilemanager\Support\CustomPathGenerator::class,
    ],
];
