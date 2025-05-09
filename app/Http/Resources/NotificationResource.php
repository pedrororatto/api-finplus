<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'type'      => class_basename($this->type),
            'data'      => $this->data,
            'read_at'   => $this->read_at,
            'created_at'=> $this->created_at->toDateTimeString(),
        ];
    }
}
