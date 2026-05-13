<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ingredient;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Services\EventCalculationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function index(): View
    {
        $purchases = Purchase::with('event', 'items.ingredient')
            ->orderBy('purchase_date', 'desc')
            ->paginate(15);
        return view('purchases.index', compact('purchases'));
    }

    public function create(Request $request, EventCalculationService $calculator): View
    {
        abort_unless(auth()->user()->canWrite('purchases'), 403);
        $events = Event::whereIn('status', ['new', 'confirmed'])->get();
        $ingredients = Ingredient::all();
        $selectedEvent = null;
        $shoppingList = collect();

        if ($request->filled('event_id')) {
            $selectedEvent = Event::with('dishes.ingredients')->findOrFail($request->event_id);
            $shoppingList = $calculator->getShoppingList($selectedEvent);
        }

        return view('purchases.form', compact('events', 'ingredients', 'selectedEvent', 'shoppingList'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('purchases'), 403);
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'purchase_date' => 'required|date',
            'status' => 'required|string|in:pending,completed,cancelled',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.ingredient_id' => 'required|exists:ingredients,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.cost' => 'required|numeric|min:0',
        ]);

        $purchase = Purchase::create([
            'event_id' => $validated['event_id'] ?? null,
            'purchase_date' => $validated['purchase_date'],
            'status' => $validated['status'],
            'total_cost' => 0,
            'notes' => $validated['notes'] ?? null,
        ]);

        $totalCost = 0;
        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'ingredient_id' => $item['ingredient_id'],
                    'quantity' => $item['quantity'],
                    'cost' => $item['cost'],
                ]);
                $totalCost += $item['cost'];
            }
        }

        $purchase->update(['total_cost' => $totalCost]);

        return redirect()->route('purchases.index')->with('success', 'Закупка создана');
    }

    public function show(Purchase $purchase): View
    {
        $purchase->load('event', 'items.ingredient');
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase): View
    {
        abort_unless(auth()->user()->canWrite('purchases'), 403);
        $purchase->load('items');
        $events = Event::all();
        $ingredients = Ingredient::all();
        return view('purchases.form', compact('purchase', 'events', 'ingredients'));
    }

    public function update(Request $request, Purchase $purchase): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('purchases'), 403);
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'purchase_date' => 'required|date',
            'status' => 'required|string|in:pending,completed,cancelled',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.ingredient_id' => 'required|exists:ingredients,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.cost' => 'required|numeric|min:0',
        ]);

        $purchase->update([
            'event_id' => $validated['event_id'] ?? null,
            'purchase_date' => $validated['purchase_date'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $purchase->items()->delete();

        $totalCost = 0;
        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'ingredient_id' => $item['ingredient_id'],
                    'quantity' => $item['quantity'],
                    'cost' => $item['cost'],
                ]);
                $totalCost += $item['cost'];
            }
        }

        $purchase->update(['total_cost' => $totalCost]);

        return redirect()->route('purchases.index')->with('success', 'Закупка обновлена');
    }

    public function destroy(Purchase $purchase): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('purchases'), 403);
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Закупка удалена');
    }

    public function complete(Purchase $purchase): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('purchases'), 403);
        $purchase->update(['status' => 'completed']);

        foreach ($purchase->items as $item) {
            $ingredient = $item->ingredient;
            if ($ingredient->category === 'frozen') {
                $refrigerator = \App\Models\Refrigerator::first();
                if ($refrigerator) {
                    \App\Models\Inventory::create([
                        'refrigerator_id' => $refrigerator->id,
                        'ingredient_id' => $item->ingredient_id,
                        'quantity' => $item->quantity,
                    ]);
                }
            }
        }

        return redirect()->route('purchases.index')->with('success', 'Закупка завершена, заморозка добавлена в холодильник');
    }
}
