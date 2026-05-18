<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IngredientController extends Controller
{
    public function index(Request $request): View
    {
        $query = Ingredient::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $ingredients = $query->paginate(15)->withQueryString();
        return view('ingredients.index', compact('ingredients'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()->canWrite('ingredients'), 403);
        return view('ingredients.form');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('ingredients'), 403);
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
        abort_unless(auth()->user()->canWrite('ingredients'), 403);
        return view('ingredients.form', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $ingredient): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('ingredients'), 403);
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
        abort_unless(auth()->user()->canWrite('ingredients'), 403);
        $ingredient->delete();
        return redirect()->route('ingredients.index')->with('success', 'Ингредиент удален');
    }
}
