<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Cafe CRM API',
    description: 'API для управления мероприятиями кафе',
)]
#[OA\PathParameter(
    parameter: 'eventId',
    name: 'id',
    description: 'ID мероприятия',
    required: true,
    schema: new OA\Schema(type: 'integer'),
)]
#[OA\SecurityScheme(
    securityScheme: 'Bearer',
    type: 'http',
    scheme: 'bearer',
)]
class Annotations
{
}
