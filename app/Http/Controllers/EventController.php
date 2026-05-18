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
    public function index(Request $request): View
    {
        $query = Event::with('dishes');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('client_phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($eventType = $request->input('event_type')) {
            $query->where('event_type', $eventType);
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('event_date', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('event_date', '<=', $dateTo);
        }

        $events = $query->orderBy('event_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        $eventTypes = Event::TYPES;

        return view('events.index', compact('events', 'eventTypes'));
    }

    public function create(Request $request): View
    {
        abort_unless(auth()->user()->canWrite('events'), 403);
        $dishes = Dish::where('is_active', true)->get();
        $eventTypes = Event::TYPES;
        $previousEvents = collect();

        if ($phone = $request->query('phone')) {
            $previousEvents = Event::byPhone($phone)->orderBy('event_date', 'desc')->take(5)->get();
        } elseif ($name = $request->query('client_name')) {
            $previousEvents = Event::byName($name)->orderBy('event_date', 'desc')->take(5)->get();
        }

        return view('events.form', compact('dishes', 'eventTypes', 'previousEvents'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('events'), 403);
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_email' => 'nullable|email|max:255',
            'event_type' => 'required|string|in:' . implode(',', array_keys(Event::TYPES)),
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
            'event_type' => $validated['event_type'],
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
        $finance = [
            'type_label' => $event->type_label,
            'type_price' => $event->type_price,
            'service_price' => $event->service_price,
            'menu_price' => $event->menu_price,
            'total_price' => $event->total_price,
            'ingredient_cost' => $event->ingredient_cost,
            'expected_profit' => $event->expected_profit,
        ];
        $previousEvents = $event->previousEvents($event->id);
        $auditLogs = $event->auditLogs()->with('user')->get();
        return view('events.show', compact('event', 'requirements', 'finance', 'previousEvents', 'auditLogs'));
    }

    public function edit(Event $event): View
    {
        abort_unless(auth()->user()->canWrite('events'), 403);
        $event->load('dishes');
        $dishes = Dish::where('is_active', true)->get();
        $eventTypes = Event::TYPES;
        $allowedStatuses = array_merge([$event->status], $event->allowedTransitions());
        $previousEvents = $event->previousEvents($event->id);
        return view('events.form', compact('event', 'dishes', 'eventTypes', 'allowedStatuses', 'previousEvents'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        abort_unless(auth()->user()->canWrite('events'), 403);
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_email' => 'nullable|email|max:255',
            'event_type' => 'required|string|in:' . implode(',', array_keys(Event::TYPES)),
            'event_date' => 'required|date',
            'event_time' => 'nullable',
            'people_count' => 'required|integer|min:1',
            'status' => 'required|string|in:new,confirmed,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
            'dishes' => 'nullable|array',
            'dishes.*.id' => 'exists:dishes,id',
            'dishes.*.servings' => 'required|integer|min:0',
        ]);

        abort_unless($event->canTransitionTo($validated['status']), 403, 'Недопустимый переход статуса');

        $event->update([
            'client_name' => $validated['client_name'],
            'client_phone' => $validated['client_phone'] ?? null,
            'client_email' => $validated['client_email'] ?? null,
            'event_type' => $validated['event_type'],
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
        $events = Event::select('id', 'client_name', 'event_type', 'event_date', 'event_time', 'people_count', 'status')
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
                        'event_type' => $event->type_label,
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

    public function printShoppingList(Event $event, EventCalculationService $calculator): View
    {
        $event->load('dishes.ingredients');
        $shoppingList = $calculator->getShoppingList($event);
        return view('events.print-shopping-list', compact('event', 'shoppingList'));
    }

    public function printMenu(Event $event): View
    {
        $event->load('dishes');
        return view('events.print-menu', compact('event'));
    }

    public function moveEvent(Request $request, Event $event): JsonResponse
    {
        $validated = $request->validate([
            'event_date' => 'required|date',
        ]);

        $event->update(['event_date' => $validated['event_date']]);

        return response()->json(['message' => 'Дата перенесена']);
    }
}
