<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    const TYPES = [
        'banquet' => ['label' => 'Банкет', 'price_per_person' => 2500],
        'buffet' => ['label' => 'Фуршет', 'price_per_person' => 1500],
        'coffee_break' => ['label' => 'Кофе-брейк', 'price_per_person' => 800],
        'wedding' => ['label' => 'Свадьба', 'price_per_person' => 3000],
        'corporate' => ['label' => 'Корпоратив', 'price_per_person' => 2000],
        'other' => ['label' => 'Другое', 'price_per_person' => 1000],
    ];

    protected $fillable = [
        'client_name',
        'client_phone',
        'client_email',
        'event_type',
        'event_date',
        'event_time',
        'people_count',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'event_time' => 'datetime',
            'people_count' => 'integer',
        ];
    }

    public static function typePrice(string $type): int
    {
        return self::TYPES[$type]['price_per_person'] ?? 1000;
    }

    public static function typeLabel(string $type): string
    {
        return self::TYPES[$type]['label'] ?? $type;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::typeLabel($this->event_type);
    }

    public function getTypePriceAttribute(): int
    {
        return self::typePrice($this->event_type);
    }

    public function getServicePriceAttribute(): float
    {
        return $this->type_price * $this->people_count;
    }

    public function getMenuPriceAttribute(): float
    {
        $this->loadMissing('dishes');
        return $this->dishes->sum(fn($d) => $d->price_per_person * $d->pivot->servings);
    }

    public function getTotalPriceAttribute(): float
    {
        return $this->service_price + $this->menu_price;
    }

    public function getIngredientCostAttribute(): float
    {
        $this->loadMissing('dishes.ingredients');
        $cost = 0;
        foreach ($this->dishes as $dish) {
            $servings = $dish->pivot->servings;
            foreach ($dish->ingredients as $ingredient) {
                $qtyPerPerson = $ingredient->pivot->quantity_per_person;
                $costPerUnit = $ingredient->cost_per_unit ?? 0;
                $cost += $costPerUnit * $qtyPerPerson * $servings;
            }
        }
        return $cost;
    }

    public function getExpectedProfitAttribute(): float
    {
        return $this->total_price - $this->ingredient_cost;
    }

    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dish::class, 'event_dish')
            ->withPivot('servings')
            ->withTimestamps();
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}
