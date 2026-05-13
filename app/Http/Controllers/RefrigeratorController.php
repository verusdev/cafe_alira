<?php

namespace App\Http\Controllers;

use App\Models\Refrigerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RefrigeratorController extends Controller
{
    public function index(): View
    {
        $refrigerators = Refrigerator::withCount('inventories')->paginate(15);
        return view('refrigerators.index', compact('refrigerators'));
    }

    public function create(): View
    {
        return view('refrigerators.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Refrigerator::create($validated);

        return redirect()->route('refrigerators.index')->with('success', 'Холодильник добавлен');
    }

    public function show(Refrigerator $refrigerator): View
    {
        $refrigerator->load('inventories.ingredient');
        return view('refrigerators.show', compact('refrigerator'));
    }

    public function edit(Refrigerator $refrigerator): View
    {
        return view('refrigerators.form', compact('refrigerator'));
    }

    public function update(Request $request, Refrigerator $refrigerator): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $refrigerator->update($validated);

        return redirect()->route('refrigerators.index')->with('success', 'Холодильник обновлен');
    }

    public function destroy(Refrigerator $refrigerator): RedirectResponse
    {
        $refrigerator->delete();
        return redirect()->route('refrigerators.index')->with('success', 'Холодильник удален');
    }
}
