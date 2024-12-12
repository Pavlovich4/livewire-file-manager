<?php

use Pavlovich4\LivewireFilemanager\Tests\TestCase;
use Pavlovich4\LivewireFilemanager\Components\FileManager;
use Livewire\Livewire;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(TestCase::class);

test('can mount file manager component', function () {
    Livewire::test(FileManager::class)
        ->assertOk()
        ->assertViewIs('livewire-filemanager::components.file-manager');
});

test('can upload file', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('test.jpg');

    Livewire::test(FileManager::class)
        ->set('uploadedFiles', [$file])
        ->call('handleFileUpload')
        ->assertOk();

    Storage::disk('public')->assertExists('test.jpg');
});

test('can create folder', function () {
    Livewire::test(FileManager::class)
        ->set('newFolderName', 'Test Folder')
        ->call('createFolder')
        ->assertOk();

    $this->assertDatabaseHas('folders', [
        'name' => 'Test Folder',
        'parent_id' => null
    ]);
});

test('can delete folder', function () {
    // Create a folder first
    $folder = \Pavlovich4\LivewireFilemanager\Models\Folder::create([
        'name' => 'Test Folder',
        'path' => 'test-folder'
    ]);

    Livewire::test(FileManager::class)
        ->call('deleteFolder', $folder->id)
        ->assertOk();

    $this->assertDatabaseMissing('folders', ['id' => $folder->id]);
});

test('can rename folder', function () {
    $folder = \Pavlovich4\LivewireFilemanager\Models\Folder::create([
        'name' => 'Old Name',
        'path' => 'old-name'
    ]);

    Livewire::test(FileManager::class)
        ->set('editingName', $folder->id)
        ->set('editingType', 'folder')
        ->set('newFolderName', 'New Name')
        ->call('renameFolder', $folder->id)
        ->assertOk();

    $this->assertDatabaseHas('folders', [
        'id' => $folder->id,
        'name' => 'New Name'
    ]);
});

test('can navigate between folders', function () {
    $folder = \Pavlovich4\LivewireFilemanager\Models\Folder::create([
        'name' => 'Test Folder',
        'path' => 'test-folder'
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

test('can download file', function () {
    $file = \Pavlovich4\LivewireFilemanager\Models\File::create([
        'name' => 'test.jpg',
        'mime_type' => 'image/jpeg',
        'size' => 1000,
        'path' => 'test.jpg'
    ]);

    $response = Livewire::test(FileManager::class)
        ->call('downloadFile', $file->id);

    $response->assertStatus(200);
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
        'path' => 'parent'
    ]);

    $child = \Pavlovich4\LivewireFilemanager\Models\Folder::create([
        'name' => 'Child',
        'path' => 'parent/child',
        'parent_id' => $parent->id
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
        'path' => 'test.jpg'
    ]);

    Livewire::test(FileManager::class)
        ->call('selectFile', $file->id)
        ->assertSet('selectedFile.id', $file->id)
        ->assertSet('showFilePreview', true);
});
