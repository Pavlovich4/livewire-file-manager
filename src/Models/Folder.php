<?php

namespace Pavlovich4\LivewireFilemanager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Folder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'parent_id',
        'path',
        'order',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($folder) {
            // Get the storage disk
            $disk = Storage::disk(config('livewire-filemanager.disk', 'public'));

            // Delete physical folder and all its contents
            if ($disk->exists($folder->path)) {
                $disk->deleteDirectory($folder->path);
            }

            // Delete all files first to ensure media is properly cleaned up
            if ($folder->files) {
                $folder->files->each(function ($file) {
                    $file->delete();
                });
            }

            // Then delete all child folders recursively
            if ($folder->children) {
                $folder->children->each(function ($childFolder) {
                    $childFolder->delete();
                });
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Folder::class, 'parent_id')->orderBy('order');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function getFullPathAttribute()
    {
        $path = $this->path;
        if ($this->parent) {
            $path = $this->parent->full_path.'/'.$path;
        }

        return $path;
    }

    public function allFiles(): HasMany
    {
        return $this->files()->union(
            File::whereIn('folder_id', $this->allChildren()->pluck('id'))
        );
    }

    public function allChildren(): Collection
    {
        return $this->children()->with('children')->get()->flatMap(function ($child) {
            return collect([$child])->merge($child->allChildren());
        });
    }
}
