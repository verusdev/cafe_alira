@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm">Блюда</h3>
            <p class="text-3xl font-bold">{{ $stats['dishes_count'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm">Ингредиенты</h3>
            <p class="text-3xl font-bold">{{ $stats['ingredients_count'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm">Активные мероприятия</h3>
            <p class="text-3xl font-bold">{{ $stats['active_events'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm">Всего мероприятий</h3>
            <p class="text-3xl font-bold">{{ $stats['total_events'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Ближайшие мероприятия</h2>
            @if($stats['upcoming_events']->count())
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="pb-2">Клиент</th>
                            <th class="pb-2">Дата</th>
                            <th class="pb-2">Гостей</th>
                            <th class="pb-2">Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['upcoming_events'] as $event)
                            <tr class="border-t">
                                <td class="py-2">{{ $event->client_name }}</td>
                                <td class="py-2">{{ $event->event_date->format('d.m.Y') }}</td>
                                <td class="py-2">{{ $event->people_count }}</td>
                                <td class="py-2">{{ $event->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500">Нет ближайших мероприятий</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Последние закупки</h2>
            @if($stats['recent_purchases']->count())
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="pb-2">Дата</th>
                            <th class="pb-2">Сумма</th>
                            <th class="pb-2">Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['recent_purchases'] as $purchase)
                            <tr class="border-t">
                                <td class="py-2">{{ $purchase->purchase_date->format('d.m.Y') }}</td>
                                <td class="py-2">{{ number_format($purchase->total_cost, 2) }} ₽</td>
                                <td class="py-2">{{ $purchase->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500">Нет закупок</p>
            @endif
        </div>
    </div>
@endsection
