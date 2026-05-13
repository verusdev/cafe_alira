<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Event;
use App\Models\Ingredient;
use App\Models\Purchase;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'dishes_count' => Dish::count(),
            'ingredients_count' => Ingredient::count(),
            'active_events' => Event::whereIn('status', ['new', 'confirmed'])->count(),
            'total_events' => Event::count(),
            'upcoming_events' => Event::where('event_date', '>=', now())
                ->where('status', '!=', 'cancelled')
                ->orderBy('event_date')
                ->take(5)
                ->get(),
            'recent_purchases' => Purchase::with('event')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('dashboard', compact('stats'));
    }
}
