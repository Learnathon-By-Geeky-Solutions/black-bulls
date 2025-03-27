<?php

namespace App\Modules\Content\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Mcq extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'mcqable_type',
        'mcqable_id',
        'question',
        'options',
        'correct_answer',
        'explanation',
        'points',
        'is_published',
    ];

    protected $casts = [
        'options' => 'array',
        'points' => 'integer',
        'is_published' => 'boolean',
    ];

    // Relationships
    public function mcqable(): MorphTo
    {
        return $this->morphTo();
    }
}
