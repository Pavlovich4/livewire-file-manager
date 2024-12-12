<?php

namespace Pavlovich4\LivewireFilemanager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FileShare extends Model
{
    protected $fillable = [
        'file_id',
        'token',
        'expires_at',
        'download_limit',
        'download_count'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'download_limit' => 'integer',
        'download_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($share) {
            $share->token = $share->token ?? Str::random(32);
        });
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function isValid()
    {
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->download_limit && $this->download_count >= $this->download_limit) {
            return false;
        }

        return true;
    }

    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }
}
