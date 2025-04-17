<?php

namespace App\Http\Resources;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Goal */
class GoalResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'category_id' => $this->category_id,
            'category_name' => $this->whenLoaded('category', fn() => $this->category->name),
            'target_amount' => number_format($this->target_amount, 2, '.', ''),
            'frequency' => $this->frequency,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'progress' => $this->whenLoaded('progress', fn() => $this->progress->sum('amount')),

        ];
    }
}
