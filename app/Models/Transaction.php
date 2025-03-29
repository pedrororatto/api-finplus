<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'type',
        'category_id',
        'description',
        'amount',
        'date',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('description', 'like', '%' . request('search') . '%');
        }

        if ($filters['category'] ?? false) {
            $query->where('category_id', request('category'));
        }

        if ($filters['type'] ?? false) {
            $query->where('type', request('type'));
        }

        if ($filters['start_date'] && $filters['end_date'] ?? false) {

            $startDateTime =  request('start_date')." ". "00:00:00";
            $endDateTime = request('end_date')." "."23:59:59";
            $query->whereBetween('date', [
                $startDateTime,
                $endDateTime
            ]);
        }
    }
}
