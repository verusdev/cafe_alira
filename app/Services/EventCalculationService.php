<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Ingredient;
use Illuminate\Support\Collection;

class EventCalculationService
{
    public function calculateRequiredIngredients(Event $event): Collection
    {
        $event->load('dishes.ingredients');
        $requirements = collect();

        foreach ($event->dishes as $dish) {
            $servings = $dish->pivot->servings;

            foreach ($dish->ingredients as $ingredient) {
                $qtyPerPerson = $ingredient->pivot->quantity_per_person;
                $totalRequired = $qtyPerPerson * $servings;

                if ($requirements->has($ingredient->id)) {
                    $existing = $requirements->get($ingredient->id);
                    $existing['required'] += $totalRequired;
                    $requirements->put($ingredient->id, $existing);
                } else {
                    $requirements->put($ingredient->id, [
                        'ingredient' => $ingredient,
                        'required' => $totalRequired,
                        'in_stock' => 0,
                        'to_buy' => $totalRequired,
                    ]);
                }
            }
        }

        $this->calculateStock($requirements);

        return $requirements;
    }

    public function calculateRequiredForMultipleEvents($events): Collection
    {
        $requirements = collect();

        foreach ($events as $event) {
            $eventReq = $this->calculateRequiredIngredients($event);

            foreach ($eventReq as $ingredientId => $data) {
                if ($requirements->has($ingredientId)) {
                    $existing = $requirements->get($ingredientId);
                    $existing['required'] += $data['required'];
                    $existing['to_buy'] += $data['to_buy'];
                    $requirements->put($ingredientId, $existing);
                } else {
                    $requirements->put($ingredientId, $data);
                }
            }
        }

        return $requirements;
    }

    public function getShoppingList(Event $event): Collection
    {
        $requirements = $this->calculateRequiredIngredients($event);

        return $requirements->filter(fn($item) => $item['to_buy'] > 0);
    }

    protected function calculateStock(Collection $requirements): void
    {
        $ingredientIds = $requirements->keys()->toArray();

        $stock = \App\Models\Inventory::whereIn('ingredient_id', $ingredientIds)
            ->where(function ($q) {
                $q->whereNull('expiration_date')
                    ->orWhere('expiration_date', '>=', now());
            })
            ->get()
            ->groupBy('ingredient_id');

        foreach ($requirements as $ingredientId => &$data) {
            if ($stock->has($ingredientId)) {
                $totalInStock = $stock->get($ingredientId)->sum('quantity');
                $data['in_stock'] = $totalInStock;
                $data['to_buy'] = max(0, $data['required'] - $totalInStock);
            }
        }
    }
}
