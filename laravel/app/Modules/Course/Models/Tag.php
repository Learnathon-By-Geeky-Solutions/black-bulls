<?php

namespace App\Modules\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Relationships
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_tags')
            ->withTimestamps();
    }
}
