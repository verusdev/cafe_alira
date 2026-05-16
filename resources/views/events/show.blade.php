@extends('layouts.app')

@section('title', 'Мероприятие: ' . $event->client_name)

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">{{ $event->client_name }}</h1>
        <div>
            @if (auth()->user()->canWrite('events'))
                <a href="{{ route('events.edit', $event) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Редактировать</a>
            @endif
            <a href="{{ route('events.print-menu', $event) }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700" target="_blank">🖨 Меню</a>
            <a href="{{ route('events.shopping-list', $event) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Список закупок</a>
            <a href="{{ route('events.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Назад</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-gray-500">Дата</p>
                <p class="font-bold">{{ $event->event_date->format('d.m.Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Время</p>
                <p class="font-bold">{{ $event->event_time ? date('H:i', strtotime($event->event_time)) : '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Гостей</p>
                <p class="font-bold">{{ $event->people_count }}</p>
            </div>
            <div>
                <p class="text-gray-500">Статус</p>
                <p class="font-bold"><span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $event->status_color }}">{{ $event->status_label }}</span></p>
            </div>
            <div>
                <p class="text-gray-500">Телефон</p>
                <p class="font-bold">{{ $event->client_phone ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Email</p>
                <p class="font-bold">{{ $event->client_email ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Тип мероприятия</p>
                <p class="font-bold">{{ $event->type_label }}</p>
            </div>
        </div>
        @if($event->notes)
            <div class="mt-4">
                <p class="text-gray-500">Заметки</p>
                <p>{{ $event->notes }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-4">
        <h2 class="text-xl font-bold mb-4">Меню</h2>
        @if($event->dishes->count())
            <table class="w-full mb-4">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Блюдо</th>
                        <th class="px-4 py-3 text-left">Порций</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($event->dishes as $dish)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $dish->name }}</td>
                            <td class="px-4 py-3">{{ $dish->pivot->servings }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500 mb-4">Меню не составлено</p>
        @endif
    </div>

    @if(auth()->user()->isManager())
        <div class="bg-white rounded-lg shadow p-6 mb-4 border-2 border-yellow-400">
            <h2 class="text-xl font-bold mb-4">Финансы</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-gray-500 text-sm">Тип мероприятия</p>
                    <p class="font-bold text-lg">{{ $finance['type_label'] }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Базовая цена</p>
                    <p class="font-bold text-lg">{{ number_format($finance['type_price'], 0, ',', ' ') }} ₽/чел</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Стоимость услуги</p>
                    <p class="font-bold text-lg">{{ number_format($finance['service_price'], 2, ',', ' ') }} ₽</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Стоимость меню</p>
                    <p class="font-bold text-lg">{{ number_format($finance['menu_price'], 2, ',', ' ') }} ₽</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Итого клиенту</p>
                    <p class="font-bold text-xl text-blue-600">{{ number_format($finance['total_price'], 2, ',', ' ') }} ₽</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Затраты на продукты</p>
                    <p class="font-bold text-lg">{{ number_format($finance['ingredient_cost'], 2, ',', ' ') }} ₽</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Ожидаемая прибыль</p>
                    <p class="font-bold text-xl {{ $finance['expected_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($finance['expected_profit'], 2, ',', ' ') }} ₽
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if($previousEvents->isNotEmpty())
        <div class="bg-white rounded-lg shadow p-6 mb-4">
            <h2 class="text-xl font-bold mb-4">Клиент также заказывал</h2>
            <div class="space-y-2">
                @foreach($previousEvents as $prev)
                    <a href="{{ route('events.show', $prev) }}"
                       class="block p-3 border rounded hover:bg-gray-50 transition">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-medium">{{ $prev->event_date->format('d.m.Y') }}</span>
                                <span class="text-gray-500 mx-2">—</span>
                                <span>{{ $prev->type_label }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500">{{ $prev->people_count }} чел.</span>
                                @include('events.partials.status-badge', ['status' => $prev->status])
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        @if($requirements->count())
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Ингредиент</th>
                        <th class="px-4 py-3 text-left">Требуется</th>
                        <th class="px-4 py-3 text-left">В наличии</th>
                        <th class="px-4 py-3 text-left">Купить</th>
                        <th class="px-4 py-3 text-left">Ед.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requirements as $item)
                        <tr class="border-t {{ $item['to_buy'] > 0 ? 'bg-red-50' : 'bg-green-50' }}">
                            <td class="px-4 py-3">{{ $item['ingredient']->name }}</td>
                            <td class="px-4 py-3">{{ number_format($item['required'], 2) }}</td>
                            <td class="px-4 py-3">{{ number_format($item['in_stock'], 2) }}</td>
                            <td class="px-4 py-3 font-bold {{ $item['to_buy'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ number_format($item['to_buy'], 2) }}
                            </td>
                            <td class="px-4 py-3">{{ $item['ingredient']->unit }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Нет данных</p>
        @endif
    </div>
@endsection
