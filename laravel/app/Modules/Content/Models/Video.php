<?php

namespace App\Modules\Content\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Video extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'videoable_type',
        'videoable_id',
        'title',
        'description',
        'url',
        'thumbnail',
        'duration',
        'is_published',
    ];

    protected $casts = [
        'duration' => 'integer',
        'is_published' => 'boolean',
    ];

    // Relationships
    public function videoable(): MorphTo
    {
        return $this->morphTo();
    }
}
