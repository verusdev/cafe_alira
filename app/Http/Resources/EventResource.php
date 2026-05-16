<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client_name' => $this->client_name,
            'client_phone' => $this->client_phone,
            'client_email' => $this->client_email,
            'event_type' => $this->event_type,
            'event_type_label' => $this->type_label,
            'event_date' => $this->event_date->format('d.m.Y'),
            'event_time' => $this->event_time ? $this->event_time->format('H:i') : null,
            'people_count' => $this->people_count,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'status_color' => $this->status_color,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->format('d.m.Y H:i'),
            'updated_at' => $this->updated_at?->format('d.m.Y H:i'),
        ];
    }
}
