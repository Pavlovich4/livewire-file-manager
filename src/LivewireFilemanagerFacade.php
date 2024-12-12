<?php

namespace Pavlovich4\LivewireFilemanager;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Pavlovich4\LivewireFilemanager\Skeleton\SkeletonClass
 */
class LivewireFilemanagerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'livewire-filemanager';
    }
}
