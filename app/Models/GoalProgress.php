<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoalProgress extends Model
{
    protected $fillable = [
        'goal_id',
        'period_start',
        'period_end',
        'progress_amount',
        'notified_threshold'
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];
    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }
}
