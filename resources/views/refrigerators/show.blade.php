@extends('layouts.app')

@section('title', $refrigerator->name)

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">{{ $refrigerator->name }}</h1>
        <div>
            <a href="{{ route('refrigerators.edit', $refrigerator) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Редактировать</a>
            <a href="{{ route('refrigerators.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Назад</a>
        </div>
    </div>

    @if($refrigerator->location || $refrigerator->description)
        <div class="bg-white rounded-lg shadow p-6 mb-4">
            @if($refrigerator->location)
                <p><span class="text-gray-500">Расположение:</span> {{ $refrigerator->location }}</p>
            @endif
            @if($refrigerator->description)
                <p><span class="text-gray-500">Описание:</span> {{ $refrigerator->description }}</p>
            @endif
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Содержимое</h2>
            <a href="{{ route('inventory.create', ['refrigerator_id' => $refrigerator->id]) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">+ Добавить</a>
        </div>
        @if($refrigerator->inventories->count())
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Продукт</th>
                        <th class="px-4 py-3 text-left">Количество</th>
                        <th class="px-4 py-3 text-left">Годен до</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($refrigerator->inventories as $inv)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $inv->ingredient->name }}</td>
                            <td class="px-4 py-3">{{ $inv->quantity }} {{ $inv->ingredient->unit }}</td>
                            <td class="px-4 py-3">{{ $inv->expiration_date ? $inv->expiration_date->format('d.m.Y') : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Холодильник пуст</p>
        @endif
    </div>
@endsection
