<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Event;
use App\Models\Ingredient;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

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

        $monthlyRevenue = Event::select(
            DB::raw("strftime('%Y-%m', event_date) as month"),
            DB::raw('SUM(people_count * CASE event_type ' . $this->typePriceCase() . ' END) as revenue'),
            DB::raw('COUNT(*) as count')
        )
            ->where('event_date', '>=', now()->subMonths(11)->startOfMonth())
            ->where('status', '!=', 'cancelled')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $typeLabels = [
            'banquet' => 'Банкет', 'wedding' => 'Свадьба', 'corporate' => 'Корпоратив',
            'buffet' => 'Фуршет', 'coffee_break' => 'Кофе-брейк', 'other' => 'Другое',
        ];

        $typeStats = Event::select('event_type', DB::raw('COUNT(*) as count'))
            ->where('status', '!=', 'cancelled')
            ->groupBy('event_type')
            ->orderByDesc('count')
            ->get()
            ->map(fn($e) => ['label' => $typeLabels[$e->event_type] ?? $e->event_type, 'count' => $e->count]);

        $statusStats = collect(Event::STATUSES)->map(fn($label, $key) => [
            'label' => $label,
            'count' => Event::where('status', $key)->count(),
        ])->values();

        return view('dashboard', compact('stats', 'monthlyRevenue', 'typeStats', 'statusStats'));
    }

    private function typePriceCase(): string
    {
        $cases = [];
        foreach (Event::TYPES as $key => $type) {
            $cases[] = "WHEN '$key' THEN {$type['price_per_person']}";
        }
        return implode(' ', $cases);
    }
}
