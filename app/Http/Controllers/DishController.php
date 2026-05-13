<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Ingredient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DishController extends Controller
{
    public function index(): View
    {
        $dishes = Dish::with('ingredients')->paginate(15);
        return view('dishes.index', compact('dishes'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()->canWrite('dishes'), 403);
        $ingredients = Ingredient::all();
        return view('dishes.form', compact('ingredients'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('dishes'), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'price_per_person' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'ingredients' => 'nullable|array',
            'ingredients.*.id' => 'exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
        ]);

        $dish = Dish::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'price_per_person' => $validated['price_per_person'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (!empty($validated['ingredients'])) {
            $pivotData = [];
            foreach ($validated['ingredients'] as $item) {
                $pivotData[$item['id']] = ['quantity_per_person' => $item['quantity']];
            }
            $dish->ingredients()->sync($pivotData);
        }

        return redirect()->route('dishes.index')->with('success', 'Блюдо создано');
    }

    public function show(Dish $dish): View
    {
        $dish->load('ingredients');
        return view('dishes.show', compact('dish'));
    }

    public function edit(Dish $dish): View
    {
        abort_unless(auth()->user()->canWrite('dishes'), 403);
        $dish->load('ingredients');
        $ingredients = Ingredient::all();
        return view('dishes.form', compact('dish', 'ingredients'));
    }

    public function update(Request $request, Dish $dish): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('dishes'), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'price_per_person' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'ingredients' => 'nullable|array',
            'ingredients.*.id' => 'exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
        ]);

        $dish->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'price_per_person' => $validated['price_per_person'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->has('ingredients')) {
            $pivotData = [];
            foreach ($validated['ingredients'] as $item) {
                $pivotData[$item['id']] = ['quantity_per_person' => $item['quantity']];
            }
            $dish->ingredients()->sync($pivotData);
        } else {
            $dish->ingredients()->detach();
        }

        return redirect()->route('dishes.index')->with('success', 'Блюдо обновлено');
    }

    public function destroy(Dish $dish): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('dishes'), 403);
        $dish->delete();
        return redirect()->route('dishes.index')->with('success', 'Блюдо удалено');
    }
}
