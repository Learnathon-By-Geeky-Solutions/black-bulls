<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CourseAttachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'mime_type',
        'file_size',
        'file_extension',
        'download_count',
        'view_count',
        'is_downloadable',
        'is_preview_available',
        'is_public',
        'access_restrictions',
        'metadata',
        'settings',
        'storage_driver',
        'version',
        'last_downloaded_at',
        'last_viewed_at',
        'expires_at',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'download_count' => 'integer',
        'view_count' => 'integer',
        'is_downloadable' => 'boolean',
        'is_preview_available' => 'boolean',
        'is_public' => 'boolean',
        'access_restrictions' => 'array',
        'metadata' => 'array',
        'settings' => 'array',
        'last_downloaded_at' => 'datetime',
        'last_viewed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the parent attachable model (course, lesson, chapter, etc.).
     */
    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
} 