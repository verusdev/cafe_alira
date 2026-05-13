<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Event;
use App\Services\EventCalculationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = Event::with('dishes')
            ->orderBy('event_date', 'desc')
            ->paginate(15);
        return view('events.index', compact('events'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()->canWrite('events'), 403);
        $dishes = Dish::where('is_active', true)->get();
        return view('events.form', compact('dishes'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('events'), 403);
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_email' => 'nullable|email|max:255',
            'event_date' => 'required|date',
            'event_time' => 'nullable',
            'people_count' => 'required|integer|min:1',
            'status' => 'required|string|in:new,confirmed,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
            'dishes' => 'nullable|array',
            'dishes.*.id' => 'exists:dishes,id',
            'dishes.*.servings' => 'required|integer|min:0',
        ]);

        $event = Event::create([
            'client_name' => $validated['client_name'],
            'client_phone' => $validated['client_phone'] ?? null,
            'client_email' => $validated['client_email'] ?? null,
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'] ?? null,
            'people_count' => $validated['people_count'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        if (!empty($validated['dishes'])) {
            $pivotData = [];
            foreach ($validated['dishes'] as $item) {
                $pivotData[$item['id']] = ['servings' => $item['servings']];
            }
            $event->dishes()->sync($pivotData);
        }

        return redirect()->route('events.index')->with('success', 'Мероприятие создано');
    }

    public function show(Event $event, EventCalculationService $calculator): View
    {
        $event->load('dishes.ingredients');
        $requirements = $calculator->calculateRequiredIngredients($event);
        return view('events.show', compact('event', 'requirements'));
    }

    public function edit(Event $event): View
    {
        abort_unless(auth()->user()->canWrite('events'), 403);
        $event->load('dishes');
        $dishes = Dish::where('is_active', true)->get();
        return view('events.form', compact('event', 'dishes'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('events'), 403);
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_email' => 'nullable|email|max:255',
            'event_date' => 'required|date',
            'event_time' => 'nullable',
            'people_count' => 'required|integer|min:1',
            'status' => 'required|string|in:new,confirmed,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
            'dishes' => 'nullable|array',
            'dishes.*.id' => 'exists:dishes,id',
            'dishes.*.servings' => 'required|integer|min:0',
        ]);

        $event->update([
            'client_name' => $validated['client_name'],
            'client_phone' => $validated['client_phone'] ?? null,
            'client_email' => $validated['client_email'] ?? null,
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'] ?? null,
            'people_count' => $validated['people_count'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($request->has('dishes')) {
            $pivotData = [];
            foreach ($validated['dishes'] as $item) {
                $pivotData[$item['id']] = ['servings' => $item['servings']];
            }
            $event->dishes()->sync($pivotData);
        } else {
            $event->dishes()->detach();
        }

        return redirect()->route('events.index')->with('success', 'Мероприятие обновлено');
    }

    public function destroy(Event $event): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('events'), 403);
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Мероприятие удалено');
    }

    public function calendar(): View
    {
        return view('events.calendar');
    }

    public function calendarData(): JsonResponse
    {
        $events = Event::select('id', 'client_name', 'event_date', 'event_time', 'people_count', 'status')
            ->get()
            ->map(function ($event) {
                $statusColors = [
                    'new' => '#3b82f6',
                    'confirmed' => '#10b981',
                    'in_progress' => '#f59e0b',
                    'completed' => '#6b7280',
                    'cancelled' => '#ef4444',
                ];

                $title = $event->client_name . ' (' . $event->people_count . ' чел.)';

                return [
                    'id' => $event->id,
                    'title' => $title,
                    'start' => $event->event_date->format('Y-m-d') . ($event->event_time ? 'T' . $event->event_time->format('H:i:s') : ''),
                    'backgroundColor' => $statusColors[$event->status] ?? '#3b82f6',
                    'borderColor' => $statusColors[$event->status] ?? '#3b82f6',
                    'textColor' => '#ffffff',
                    'url' => route('events.show', $event),
                    'extendedProps' => [
                        'status' => $event->status,
                        'people_count' => $event->people_count,
                    ],
                ];
            });

        return response()->json($events);
    }

    public function shoppingList(Event $event, EventCalculationService $calculator): View
    {
        $event->load('dishes.ingredients');
        $shoppingList = $calculator->getShoppingList($event);

        $purchase = $event->purchases()->where('status', 'pending')->first();

        return view('events.shopping-list', compact('event', 'shoppingList', 'purchase'));
    }
}
