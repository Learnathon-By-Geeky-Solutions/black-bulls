<?php

namespace App\Modules\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transcript extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transcriptable_type',
        'transcriptable_id',
        'title',
        'content',
        'timestamps',
        'is_published',
    ];

    protected $casts = [
        'timestamps' => 'array',
        'is_published' => 'boolean',
    ];

    // Relationships
    public function transcriptable(): MorphTo
    {
        return $this->morphTo();
    }
}
