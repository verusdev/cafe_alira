<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Ingredient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DishController extends Controller
{
    public function index(Request $request): View
    {
        $query = Dish::with('ingredients');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->has('is_active') && $request->input('is_active') !== '') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $dishes = $query->paginate(15)->withQueryString();
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'ingredients' => 'nullable|array',
            'ingredients.*.id' => 'exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
        ]);

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'price_per_person' => $validated['price_per_person'],
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('dishes', 'public');
        }

        $dish = Dish::create($data);

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'ingredients' => 'nullable|array',
            'ingredients.*.id' => 'exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
        ]);

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'price_per_person' => $validated['price_per_person'],
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            if ($dish->image) {
                Storage::disk('public')->delete($dish->image);
            }
            $data['image'] = $request->file('image')->store('dishes', 'public');
        }

        $dish->update($data);

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
        if ($dish->image) {
            Storage::disk('public')->delete($dish->image);
        }
        $dish->delete();
        return redirect()->route('dishes.index')->with('success', 'Блюдо удалено');
    }
}
