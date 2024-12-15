<?php

namespace Pavlovich4\LivewireFilemanager\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Pavlovich4\LivewireFilemanager\Support\CustomPathGenerator;

class File extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'folder_id',
        'mime_type',
        'size',
        'path'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($file) {
            // Delete the physical file
            if ($file->getFirstMedia()) {
                $file->getFirstMedia()->delete();
            }

            // Delete from storage if exists
            if (Storage::disk(config('livewire-filemanager.disk', 'public'))->exists($file->path)) {
                Storage::disk(config('livewire-filemanager.disk', 'public'))->delete($file->path);
            }
        });
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('default', 'thumb');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')
            ->singleFile()
            ->useDisk(config('livewire-filemanager.disk', 'public'))
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200)
                    ->nonQueued();
            });
    }
}
