<?php

namespace App\Modules\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chapter_id',
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
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function tutorial(): HasOne
    {
        return $this->hasOne(Tutorial::class);
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

    /**
     * Get the course through chapter and course section relationship.
     */
    public function course(): HasOneThrough
    {
        return $this->hasOneThrough(
            Course::class,
            Chapter::class,
            'id', // Foreign key on chapters table
            'id', // Foreign key on courses table
            'chapter_id', // Local key on lessons table
            'course_section_id' // Local key on chapters table
        )->join('course_sections', 'course_sections.id', '=', 'chapters.course_section_id')
         ->select('courses.*');
    }
}

