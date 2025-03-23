<?php

namespace App\Modules\Course\Models;

use App\Modules\Auth\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseEnrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'user_id',
        'price_paid',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'price_paid' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
