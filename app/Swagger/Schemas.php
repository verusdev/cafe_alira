<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Event',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'client_name', type: 'string'),
        new OA\Property(property: 'client_phone', type: 'string', nullable: true),
        new OA\Property(property: 'client_email', type: 'string', nullable: true),
        new OA\Property(property: 'event_type', type: 'string'),
        new OA\Property(property: 'event_type_label', type: 'string'),
        new OA\Property(property: 'event_date', type: 'string'),
        new OA\Property(property: 'event_time', type: 'string', nullable: true),
        new OA\Property(property: 'people_count', type: 'integer'),
        new OA\Property(property: 'status', type: 'string'),
        new OA\Property(property: 'status_label', type: 'string'),
        new OA\Property(property: 'status_color', type: 'string'),
        new OA\Property(property: 'notes', type: 'string', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'EventDetail',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/Event'),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'dishes',
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Dish')
                ),
            ],
            type: 'object'
        ),
    ]
)]
#[OA\Schema(
    schema: 'Dish',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'category', type: 'string'),
        new OA\Property(property: 'price_per_person', type: 'number', format: 'float'),
        new OA\Property(property: 'servings', type: 'integer', nullable: true),
    ],
    type: 'object'
)]
class Schemas
{
}
