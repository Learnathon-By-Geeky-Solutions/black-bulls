<?php

namespace App\Modules\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Chapter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_section_id',
        'title',
        'description',
        'order',
        'is_published',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_published' => 'boolean',
    ];

    // Relationships
    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function videos(): MorphMany
    {
        return $this->morphMany(Video::class, 'videoable');
    }

    public function mcqs(): MorphMany
    {
        return $this->morphMany(Mcq::class, 'mcqable');
    }

    public function transcripts(): MorphMany
    {
        return $this->morphMany(Transcript::class, 'transcriptable');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(CourseAttachment::class, 'attachable');
    }

    public function progress(): MorphMany
    {
        return $this->morphMany(ProgressTracking::class, 'trackable');
    }

    public function analytics(): MorphMany
    {
        return $this->morphMany(AnalyticsTracking::class, 'trackable');
    }
}
