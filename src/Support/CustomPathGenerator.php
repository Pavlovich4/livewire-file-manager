<?php

namespace Pavlovich4\LivewireFilemanager\Support;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $file = $media->model;
        $folder = $file->folder;

        return $folder ? $folder->path . '/' . $file->id . '/' : $file->id . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . '/conversions';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . '/responsive';
    }
}
