<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Pavlovich4\LivewireFilemanager\Livewire\FileManager;

test('can mount file manager component', function () {
    Livewire::test(FileManager::class)
        ->assertOk()
        ->assertViewIs('livewire-filemanager::livewire.file-manager');
});

test('can upload file and download file', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('test.jpg');

    Livewire::test(FileManager::class)
        ->set('uploadedFiles', [$file])
        ->call('handleFileUpload')
        ->assertOk();

    $uploadedFile = \Pavlovich4\LivewireFilemanager\Models\File::first();

    expect($uploadedFile->name)->toBe('test.jpg')->and($uploadedFile->path)->toBe('test.jpg');

    $response = Livewire::test(FileManager::class)
        ->call('downloadFile', $uploadedFile->id);

    $response->assertStatus(200);
});

test('can create folder', function () {
    Livewire::test(FileManager::class)
        ->set('newFolderName', 'Test Folder')
        ->call('createFolder')
        ->assertOk();

    $this->assertDatabaseHas('folders', [
        'name' => 'Test Folder',
        'parent_id' => null,
    ]);
});

test('can delete folder', function () {
    // Create a folder first
    $folder = \Pavlovich4\LivewireFilemanager\Models\Folder::create([
        'name' => 'Test Folder',
        'path' => 'test-folder',
    ]);
    Livewire::test(FileManager::class)
        ->call('deleteFolder', $folder->id)
        ->assertOk();

    expect(\Pavlovich4\LivewireFilemanager\Models\Folder::all()->toArray())->toBeEmpty();

});

test('can rename folder', function () {
    $folder = \Pavlovich4\LivewireFilemanager\Models\Folder::create([
        'name' => 'Old Name',
        'path' => 'old-name',
    ]);

    Livewire::test(FileManager::class)
        ->set('editingName', $folder->id)
        ->set('editingType', 'folder')
        ->set('newFolderName', 'New Name')
        ->call('renameFolder', $folder->id)
        ->assertOk();

    $this->assertDatabaseHas('folders', [
        'id' => $folder->id,
        'name' => 'New Name',
    ]);
});

test('can navigate between folders', function () {
    $folder = \Pavlovich4\LivewireFilemanager\Models\Folder::create([
        'name' => 'Test Folder',
        'path' => 'test-folder',
    ]);

    Livewire::test(FileManager::class)
        ->call('navigateToFolder', $folder->id)
        ->assertSet('currentFolder', $folder->id);
});

test('can toggle view mode', function () {
    Livewire::test(FileManager::class)
        ->assertSet('isGrid', true)
        ->call('toggleView')
        ->assertSet('isGrid', false);
});

test('validates file upload size', function () {
    $file = UploadedFile::fake()->create('large.txt', 102401); // 100MB + 1KB

    Livewire::test(FileManager::class)
        ->set('uploadedFiles', [$file])
        ->call('handleFileUpload')
        ->assertHasErrors(['uploadedFiles.*']);
});

test('can handle nested folders', function () {
    $parent = \Pavlovich4\LivewireFilemanager\Models\Folder::create([
        'name' => 'Parent',
        'path' => 'parent',
    ]);

    $child = \Pavlovich4\LivewireFilemanager\Models\Folder::create([
        'name' => 'Child',
        'path' => 'parent/child',
        'parent_id' => $parent->id,
    ]);

    Livewire::test(FileManager::class)
        ->call('navigateToFolder', $child->id)
        ->assertSet('currentFolder', $child->id)
        ->assertSee('Parent')
        ->assertSee('Child');
});

test('can handle file preview', function () {
    $file = \Pavlovich4\LivewireFilemanager\Models\File::create([
        'name' => 'test.jpg',
        'mime_type' => 'image/jpeg',
        'size' => 1000,
        'path' => 'test.jpg',
    ]);

    Livewire::test(FileManager::class)
        ->call('selectFile', $file->id)
        ->assertSet('selectedFile.id', $file->id)
        ->assertSet('showFilePreview', true);
});

test('it shows confirmation before deleting file', function () {
    $file = File::factory()->create();

    Livewire::test(FileManager::class)
        ->call('deleteFile', $file->id)
        ->assertSet('showFileDeleteConfirmation', true)
        ->assertSet('fileToDelete.id', $file->id)
        ->call('cancelFileDelete')
        ->assertSet('showFileDeleteConfirmation', false)
        ->assertSet('fileToDelete', null)
        ->call('deleteFile', $file->id)
        ->call('confirmFileDelete')
        ->assertSet('showFileDeleteConfirmation', false)
        ->assertSet('fileToDelete', null);

    $this->assertDatabaseMissing('files', ['id' => $file->id]);
})->todo();
