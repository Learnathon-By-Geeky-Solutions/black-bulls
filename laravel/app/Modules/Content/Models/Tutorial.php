<?php

namespace App\Modules\Content\Models;

use App\Modules\Course\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tutorial extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'content',
    ];

    // Relationships
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
