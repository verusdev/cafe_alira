<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IngredientController extends Controller
{
    public function index(): View
    {
        $ingredients = Ingredient::paginate(15);
        return view('ingredients.index', compact('ingredients'));
    }

    public function create(): View
    {
        return view('ingredients.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'category' => 'required|string|in:fresh,frozen',
            'cost_per_unit' => 'nullable|numeric|min:0',
        ]);

        Ingredient::create($validated);

        return redirect()->route('ingredients.index')->with('success', 'Ингредиент создан');
    }

    public function show(Ingredient $ingredient): View
    {
        $ingredient->load('dishes', 'inventories.refrigerator');
        return view('ingredients.show', compact('ingredient'));
    }

    public function edit(Ingredient $ingredient): View
    {
        return view('ingredients.form', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $ingredient): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'category' => 'required|string|in:fresh,frozen',
            'cost_per_unit' => 'nullable|numeric|min:0',
        ]);

        $ingredient->update($validated);

        return redirect()->route('ingredients.index')->with('success', 'Ингредиент обновлен');
    }

    public function destroy(Ingredient $ingredient): RedirectResponse
    {
        $ingredient->delete();
        return redirect()->route('ingredients.index')->with('success', 'Ингредиент удален');
    }
}
