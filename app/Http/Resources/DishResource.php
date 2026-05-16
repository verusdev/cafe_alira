<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DishResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'price_per_person' => (float) $this->price_per_person,
            'servings' => $this->whenPivotLoaded('dish_event', fn() => (int) $this->pivot->servings),
        ];
    }
}
