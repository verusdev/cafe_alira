@extends('layouts.app')

@section('title', 'Закупка от ' . $purchase->purchase_date->format('d.m.Y'))

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Закупка от {{ $purchase->purchase_date->format('d.m.Y') }}</h1>
        <div>
            @if(auth()->user()->canWrite('purchases') && $purchase->status == 'pending')
                <a href="{{ route('purchases.edit', $purchase) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Редактировать</a>
                <form action="{{ route('purchases.complete', $purchase) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Завершить</button>
                </form>
            @endif
            <a href="{{ route('purchases.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Назад</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-4">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div>
                <p class="text-gray-500">Дата</p>
                <p class="font-bold">{{ $purchase->purchase_date->format('d.m.Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Статус</p>
                <p class="font-bold"><span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $purchase->status_color }}">{{ $purchase->status_label }}</span></p>
            </div>
            <div>
                <p class="text-gray-500">Мероприятие</p>
                <p class="font-bold">{{ $purchase->event->client_name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Сумма</p>
                <p class="font-bold text-lg">{{ number_format($purchase->total_cost, 2) }} ₽</p>
            </div>
        </div>
        @if($purchase->notes)
            <div class="mt-4">
                <p class="text-gray-500">Заметки</p>
                <p>{{ $purchase->notes }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Позиции</h2>
        @if($purchase->items->count())
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Ингредиент</th>
                        <th class="px-4 py-3 text-left">Количество</th>
                        <th class="px-4 py-3 text-left">Стоимость</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->items as $item)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $item->ingredient->name }}</td>
                            <td class="px-4 py-3">{{ $item->quantity }} {{ $item->ingredient->unit }}</td>
                            <td class="px-4 py-3">{{ number_format($item->cost, 2) }} ₽</td>
                        </tr>
                    @endforeach
                    <tr class="border-t font-bold">
                        <td class="px-4 py-3">Итого</td>
                        <td class="px-4 py-3"></td>
                        <td class="px-4 py-3">{{ number_format($purchase->total_cost, 2) }} ₽</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Нет позиций</p>
        @endif
    </div>
@endsection
