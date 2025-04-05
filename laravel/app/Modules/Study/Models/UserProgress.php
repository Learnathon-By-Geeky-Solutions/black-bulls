<?php

namespace App\Modules\Study\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Course\Models\Lesson;
use App\Models\User;

class UserProgress extends Model
{
    protected $fillable = [
        'user_id',
        'lesson_id',
        'is_completed',
        'quiz_completed',
        'quiz_score',
        'last_position',
        'completed_at'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'quiz_completed' => 'boolean',
        'completed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
