<?php

namespace App\Modules\Progress\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AnalyticsTracking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trackable_type',
        'trackable_id',
        'user_id',
        'event_type',
        'event_data',
        'event_time',
    ];

    protected $casts = [
        'event_data' => 'array',
        'event_time' => 'datetime',
    ];

    // Relationships
    public function trackable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
