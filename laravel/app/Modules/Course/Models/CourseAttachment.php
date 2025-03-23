<?php

namespace App\Modules\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CourseAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'title',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'is_downloadable',
        'is_published',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'is_downloadable' => 'boolean',
        'is_published' => 'boolean',
    ];

    // Relationships
    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
