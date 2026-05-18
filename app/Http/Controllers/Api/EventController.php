<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventDetailResource;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Services\EventCalculationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class EventController extends Controller
{
    #[OA\Get(
        path: '/api/events',
        summary: 'Список мероприятий',
        tags: ['Events'],
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Пагинированный список мероприятий',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Event')
                        ),
                        new OA\Property(
                            property: 'meta',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer'),
                                new OA\Property(property: 'last_page', type: 'integer'),
                                new OA\Property(property: 'per_page', type: 'integer'),
                                new OA\Property(property: 'total', type: 'integer'),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Не авторизован'),
        ]
    )]
    public function index(Request $request): JsonResponse
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

        if ($type = $request->input('event_type')) {
            $query->where('event_type', $type);
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->where('event_date', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->where('event_date', '<=', $dateTo);
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(15);

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

    #[OA\Post(
        path: '/api/events',
        summary: 'Создать мероприятие',
        tags: ['Events'],
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'client_name', type: 'string', maxLength: 255),
                    new OA\Property(property: 'client_phone', type: 'string', maxLength: 50, nullable: true),
                    new OA\Property(property: 'client_email', type: 'string', format: 'email', nullable: true),
                    new OA\Property(property: 'event_type', type: 'string', enum: ['banquet', 'buffet', 'coffee_break', 'wedding', 'corporate', 'other']),
                    new OA\Property(property: 'event_date', type: 'string', format: 'date'),
                    new OA\Property(property: 'event_time', type: 'string', nullable: true),
                    new OA\Property(property: 'people_count', type: 'integer', minimum: 1),
                    new OA\Property(property: 'status', type: 'string', enum: ['new', 'confirmed', 'in_progress', 'completed', 'cancelled']),
                    new OA\Property(property: 'notes', type: 'string', nullable: true),
                    new OA\Property(
                        property: 'dishes',
                        type: 'array',
                        nullable: true,
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'servings', type: 'integer', minimum: 0),
                            ],
                            type: 'object'
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Мероприятие создано'),
            new OA\Response(response: 422, description: 'Ошибка валидации'),
            new OA\Response(response: 401, description: 'Не авторизован'),
            new OA\Response(response: 403, description: 'Доступ запрещён'),
        ]
    )]
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

    #[OA\Get(
        path: '/api/events/{id}',
        summary: 'Детали мероприятия',
        tags: ['Events'],
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/eventId'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Мероприятие с финансами и блюдами',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/EventDetail'),
                        new OA\Property(
                            property: 'finance',
                            properties: [
                                new OA\Property(property: 'service_price', type: 'number', format: 'float'),
                                new OA\Property(property: 'menu_price', type: 'number', format: 'float'),
                                new OA\Property(property: 'total_price', type: 'number', format: 'float'),
                                new OA\Property(property: 'ingredient_cost', type: 'number', format: 'float'),
                                new OA\Property(property: 'expected_profit', type: 'number', format: 'float'),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Не найдено'),
            new OA\Response(response: 401, description: 'Не авторизован'),
        ]
    )]
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

    #[OA\Put(
        path: '/api/events/{id}',
        summary: 'Обновить мероприятие',
        tags: ['Events'],
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/eventId'),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'client_name', type: 'string', maxLength: 255),
                    new OA\Property(property: 'client_phone', type: 'string', maxLength: 50, nullable: true),
                    new OA\Property(property: 'client_email', type: 'string', format: 'email', nullable: true),
                    new OA\Property(property: 'event_type', type: 'string', enum: ['banquet', 'buffet', 'coffee_break', 'wedding', 'corporate', 'other']),
                    new OA\Property(property: 'event_date', type: 'string', format: 'date'),
                    new OA\Property(property: 'event_time', type: 'string', nullable: true),
                    new OA\Property(property: 'people_count', type: 'integer', minimum: 1),
                    new OA\Property(property: 'status', type: 'string', enum: ['new', 'confirmed', 'in_progress', 'completed', 'cancelled']),
                    new OA\Property(property: 'notes', type: 'string', nullable: true),
                    new OA\Property(
                        property: 'dishes',
                        type: 'array',
                        nullable: true,
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'servings', type: 'integer', minimum: 0),
                            ],
                            type: 'object'
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Мероприятие обновлено'),
            new OA\Response(response: 422, description: 'Ошибка валидации'),
            new OA\Response(response: 403, description: 'Доступ запрещён'),
            new OA\Response(response: 401, description: 'Не авторизован'),
            new OA\Response(response: 404, description: 'Не найдено'),
        ]
    )]
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

    #[OA\Delete(
        path: '/api/events/{id}',
        summary: 'Удалить мероприятие',
        tags: ['Events'],
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/eventId'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Мероприятие удалено'),
            new OA\Response(response: 403, description: 'Доступ запрещён'),
            new OA\Response(response: 401, description: 'Не авторизован'),
            new OA\Response(response: 404, description: 'Не найдено'),
        ]
    )]
    public function destroy(Event $event): JsonResponse
    {
        abort_unless(auth()->user()->canWrite('events'), 403);

        $event->delete();

        return response()->json(['message' => 'Мероприятие удалено'], 200);
    }

    #[OA\Get(
        path: '/api/events/{id}/shopping-list',
        summary: 'Список закупок для мероприятия',
        tags: ['Events'],
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/eventId'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Список ингредиентов для закупки',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'ingredient_id', type: 'integer'),
                                    new OA\Property(property: 'ingredient_name', type: 'string'),
                                    new OA\Property(property: 'to_buy', type: 'number', format: 'float'),
                                    new OA\Property(property: 'unit', type: 'string'),
                                    new OA\Property(property: 'category', type: 'string'),
                                ],
                                type: 'object'
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Не найдено'),
            new OA\Response(response: 401, description: 'Не авторизован'),
        ]
    )]
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
