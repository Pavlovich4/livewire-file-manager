<?php

namespace Pavlovich4\LivewireFilemanager\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Pavlovich4\LivewireFilemanager\Models\{File, Folder};

class UploadFileAction
{
    public function execute(UploadedFile $uploadedFile, ?Folder $folder = null): File
    {
        // Determine the physical path for the file
        $folderPath = $folder ? $folder->path : '';
        $fileName = $uploadedFile->getClientOriginalName();
        $filePath = trim($folderPath . '/' . $fileName, '/');

        // Create the physical folder if it doesn't exist
        if ($folder) {
            $disk = Storage::disk(config('livewire-filemanager.disk', 'public'));
            if (!$disk->exists($folder->path)) {
                $disk->makeDirectory($folder->path);
            }
        }

        // Create the file record
        $file = File::create([
            'name' => $fileName,
            'folder_id' => $folder?->id,
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
            'path' => $filePath,
        ]);

        // Store the file in the correct physical location
        $file->addMedia($uploadedFile)
            ->preservingOriginal()
            ->toMediaCollection('default');

        return $file;
    }
}
