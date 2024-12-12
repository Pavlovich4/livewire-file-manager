<?php

namespace Pavlovich4\LivewireFilemanager\Actions;

use Illuminate\Support\Facades\Storage;
use Pavlovich4\LivewireFilemanager\Models\Folder;

class CreateFolderAction
{
    public function execute(string $name, ?Folder $parent = null): Folder
    {
        // Determine the folder path
        $path = $name;
        if ($parent) {
            $path = trim($parent->path . '/' . $name, '/');
        }

        // Create the physical folder
        $disk = Storage::disk(config('livewire-filemanager.disk', 'public'));
        if (!$disk->exists($path)) {
            $disk->makeDirectory($path);
        }

        // Create the folder record
        return Folder::create([
            'name' => $name,
            'parent_id' => $parent?->id,
            'path' => $path,
            'order' => Folder::where('parent_id', $parent?->id)->max('order') + 1,
        ]);
    }
}
