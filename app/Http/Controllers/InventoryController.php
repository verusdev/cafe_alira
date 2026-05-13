<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\Refrigerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(): View
    {
        $inventories = Inventory::with(['refrigerator', 'ingredient'])
            ->orderBy('expiration_date')
            ->paginate(15);
        return view('inventory.index', compact('inventories'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()->canWrite('inventory'), 403);
        $refrigerators = Refrigerator::all();
        $ingredients = Ingredient::where('category', 'frozen')->get();
        return view('inventory.form', compact('refrigerators', 'ingredients'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('inventory'), 403);
        $validated = $request->validate([
            'refrigerator_id' => 'required|exists:refrigerators,id',
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
        ]);

        Inventory::create($validated);

        return redirect()->route('inventory.index')->with('success', 'Запас добавлен');
    }

    public function show(Inventory $inventory): View
    {
        $inventory->load(['refrigerator', 'ingredient']);
        return view('inventory.show', compact('inventory'));
    }

    public function edit(Inventory $inventory): View
    {
        abort_unless(auth()->user()->canWrite('inventory'), 403);
        $refrigerators = Refrigerator::all();
        $ingredients = Ingredient::where('category', 'frozen')->get();
        return view('inventory.form', compact('inventory', 'refrigerators', 'ingredients'));
    }

    public function update(Request $request, Inventory $inventory): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('inventory'), 403);
        $validated = $request->validate([
            'refrigerator_id' => 'required|exists:refrigerators,id',
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
        ]);

        $inventory->update($validated);

        return redirect()->route('inventory.index')->with('success', 'Запас обновлен');
    }

    public function destroy(Inventory $inventory): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('inventory'), 403);
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Запас удален');
    }
}
