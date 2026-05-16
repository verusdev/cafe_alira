@extends('layouts.app')

@section('title', 'Список закупок')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Список закупок: {{ $event->client_name }}</h1>
        <div>
            @if (auth()->user()->canWrite('purchases'))
                <a href="{{ route('purchases.create', ['event_id' => $event->id]) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Оформить закупку</a>
            @endif
            <a href="{{ route('events.print-shopping-list', $event) }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700" target="_blank">🖨 Печать</a>
            <a href="{{ route('events.show', $event) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Назад</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        @if($shoppingList->count())
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Ингредиент</th>
                        <th class="px-4 py-3 text-left">Нужно купить</th>
                        <th class="px-4 py-3 text-left">Ед.</th>
                        <th class="px-4 py-3 text-left">Категория</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shoppingList as $item)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $item['ingredient']->name }}</td>
                            <td class="px-4 py-3 font-bold text-red-600">{{ number_format($item['to_buy'], 2) }}</td>
                            <td class="px-4 py-3">{{ $item['ingredient']->unit }}</td>
                            <td class="px-4 py-3">
                                <span class="{{ $item['ingredient']->category == 'frozen' ? 'text-blue-600' : 'text-orange-600' }}">
                                    {{ $item['ingredient']->category == 'frozen' ? 'Заморозка' : 'Свежие' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Все продукты есть в наличии. Закупки не требуются.</p>
        @endif
    </div>
@endsection
