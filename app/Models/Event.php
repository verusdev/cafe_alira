<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'client_name',
        'client_phone',
        'client_email',
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
