<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

uses(Pavlovich4\LivewireFilemanager\Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

function createTestFile($name = 'test.jpg', $size = 1000)
{
    return \Pavlovich4\LivewireFilemanager\Models\File::create([
        'name' => $name,
        'mime_type' => 'image/jpeg',
        'size' => $size,
        'path' => $name
    ]);
}

function createTestFolder($name = 'Test Folder', $parentId = null)
{
    return \Pavlovich4\LivewireFilemanager\Models\Folder::create([
        'name' => $name,
        'path' => \Str::slug($name),
        'parent_id' => $parentId
    ]);
}
