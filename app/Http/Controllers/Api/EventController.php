<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventDetailResource;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Services\EventCalculationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(): JsonResponse
    {
        $events = Event::with('dishes')
            ->orderBy('event_date', 'desc')
            ->paginate(15);

        return response()->json([
            'data' => EventResource::collection($events),
            'meta' => [
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'per_page' => $events->perPage(),
                'total' => $events->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
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
            'status' => 'sometimes|string|in:new,confirmed,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
            'dishes' => 'nullable|array',
            'dishes.*.id' => 'required|exists:dishes,id',
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
            'status' => $validated['status'] ?? 'new',
            'notes' => $validated['notes'] ?? null,
        ]);

        if (!empty($validated['dishes'])) {
            $pivotData = [];
            foreach ($validated['dishes'] as $item) {
                $pivotData[$item['id']] = ['servings' => $item['servings']];
            }
            $event->dishes()->sync($pivotData);
        }

        $event->load('dishes');

        return response()->json(['data' => new EventDetailResource($event)], 201);
    }

    public function show(Event $event): JsonResponse
    {
        $event->load('dishes.ingredients');

        return response()->json([
            'data' => new EventDetailResource($event),
            'finance' => [
                'service_price' => (float) $event->service_price,
                'menu_price' => (float) $event->menu_price,
                'total_price' => (float) $event->total_price,
                'ingredient_cost' => (float) $event->ingredient_cost,
                'expected_profit' => (float) $event->expected_profit,
            ],
        ]);
    }

    public function update(Request $request, Event $event): JsonResponse
    {
        abort_unless(auth()->user()->canWrite('events'), 403);

        $validated = $request->validate([
            'client_name' => 'sometimes|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_email' => 'nullable|email|max:255',
            'event_type' => 'sometimes|string|in:' . implode(',', array_keys(Event::TYPES)),
            'event_date' => 'sometimes|date',
            'event_time' => 'nullable',
            'people_count' => 'sometimes|integer|min:1',
            'status' => 'sometimes|string|in:new,confirmed,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
            'dishes' => 'nullable|array',
            'dishes.*.id' => 'required|exists:dishes,id',
            'dishes.*.servings' => 'required|integer|min:0',
        ]);

        if (isset($validated['status']) && !$event->canTransitionTo($validated['status'])) {
            return response()->json(['message' => 'Недопустимый переход статуса'], 422);
        }

        $event->update($validated);

        if ($request->has('dishes')) {
            $pivotData = [];
            foreach ($validated['dishes'] as $item) {
                $pivotData[$item['id']] = ['servings' => $item['servings']];
            }
            $event->dishes()->sync($pivotData);
        }

        $event->load('dishes');

        return response()->json(['data' => new EventDetailResource($event)]);
    }

    public function destroy(Event $event): JsonResponse
    {
        abort_unless(auth()->user()->canWrite('events'), 403);

        $event->delete();

        return response()->json(['message' => 'Мероприятие удалено'], 200);
    }

    public function shoppingList(Event $event, EventCalculationService $calculator): JsonResponse
    {
        $event->load('dishes.ingredients');
        $shoppingList = $calculator->getShoppingList($event);

        return response()->json([
            'data' => $shoppingList->map(function ($item) {
                return [
                    'ingredient_id' => $item['ingredient']->id,
                    'ingredient_name' => $item['ingredient']->name,
                    'to_buy' => (float) $item['to_buy'],
                    'unit' => $item['ingredient']->unit,
                    'category' => $item['ingredient']->category,
                ];
            }),
        ]);
    }
}
