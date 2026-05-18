<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    const STATUSES = [
        'new' => 'Новый',
        'confirmed' => 'Подтверждён',
        'in_progress' => 'В процессе',
        'completed' => 'Завершён',
        'cancelled' => 'Отменён',
    ];

    const TRANSITIONS = [
        'new' => ['confirmed', 'cancelled'],
        'confirmed' => ['in_progress', 'cancelled'],
        'in_progress' => ['completed', 'cancelled'],
        'completed' => [],
        'cancelled' => [],
    ];

    const TYPES = [
        'banquet' => ['label' => 'Банкет', 'price_per_person' => 2500],
        'buffet' => ['label' => 'Фуршет', 'price_per_person' => 1500],
        'coffee_break' => ['label' => 'Кофе-брейк', 'price_per_person' => 800],
        'wedding' => ['label' => 'Свадьба', 'price_per_person' => 3000],
        'corporate' => ['label' => 'Корпоратив', 'price_per_person' => 2000],
        'other' => ['label' => 'Другое', 'price_per_person' => 1000],
    ];

    protected static function booted(): void
    {
        static::updating(function (Event $event) {
            if ($event->isDirty('status') && auth()->check()) {
                $event->auditLogs()->create([
                    'user_id' => auth()->id(),
                    'field' => 'status',
                    'old_value' => $event->getOriginal('status'),
                    'new_value' => $event->status,
                ]);
            }
        });
    }

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

    public static function statusLabel(string $status): string
    {
        return self::STATUSES[$status] ?? $status;
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

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'new' => 'bg-blue-100 text-blue-800',
            'confirmed' => 'bg-green-100 text-green-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function allowedTransitions(): array
    {
        return self::TRANSITIONS[$this->status] ?? [];
    }

    public function canTransitionTo(string $newStatus): bool
    {
        return $newStatus === $this->status || in_array($newStatus, $this->allowedTransitions());
    }

    public function scopeByPhone($query, string $phone)
    {
        return $query->where('client_phone', $phone);
    }

    public function scopeByName($query, string $name)
    {
        return $query->where('client_name', 'like', '%' . $name . '%');
    }

    public function previousEvents(?int $excludeId = null)
    {
        $query = Event::query();
        if ($this->client_phone) {
            $query->where('client_phone', $this->client_phone);
        } elseif ($this->client_name) {
            $query->where('client_name', 'like', '%' . $this->client_name . '%');
        } else {
            return collect();
        }
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->orderBy('event_date', 'desc')->take(5)->get();
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

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class)->latest();
    }
}
