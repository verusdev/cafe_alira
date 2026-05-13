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
        $upcomingEvents = Event::where('event_date', '>=', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('event_date')
            ->take(5)
            ->get();

        $activeEvents = Event::whereIn('status', ['new', 'confirmed'])->get();

        $totalRevenue = $activeEvents->sum(fn($e) => $e->total_price);
        $totalIngredientCost = $activeEvents->sum(fn($e) => $e->ingredient_cost);
        $expectedProfit = $totalRevenue - $totalIngredientCost;
        $profitMargin = $totalRevenue > 0 ? ($expectedProfit / $totalRevenue) * 100 : 0;

        $stats = [
            'dishes_count' => Dish::count(),
            'ingredients_count' => Ingredient::count(),
            'active_events' => $activeEvents->count(),
            'total_events' => Event::count(),
            'upcoming_events' => $upcomingEvents,
            'recent_purchases' => Purchase::with('event')
                ->latest()
                ->take(5)
                ->get(),
            'total_revenue' => $totalRevenue,
            'total_ingredient_cost' => $totalIngredientCost,
            'expected_profit' => $expectedProfit,
            'profit_margin' => $profitMargin,
        ];

        return view('dashboard', compact('stats'));
    }
}
