<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $fillable = [
        'refrigerator_id',
        'ingredient_id',
        'quantity',
        'expiration_date',
    ];

    protected function casts(): array
    {
        return [
            'expiration_date' => 'date',
            'quantity' => 'decimal:4',
        ];
    }

    public function refrigerator(): BelongsTo
    {
        return $this->belongsTo(Refrigerator::class);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
