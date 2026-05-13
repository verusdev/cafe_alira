@extends('layouts.app')

@section('title', 'Запас: ' . $inventory->ingredient->name)

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">{{ $inventory->ingredient->name }}</h1>
        <div>
            <a href="{{ route('inventory.edit', $inventory) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Редактировать</a>
            <a href="{{ route('inventory.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Назад</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-gray-500">Холодильник</p>
                <p class="font-bold">{{ $inventory->refrigerator->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Ингредиент</p>
                <p class="font-bold">{{ $inventory->ingredient->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Количество</p>
                <p class="font-bold">{{ $inventory->quantity }} {{ $inventory->ingredient->unit }}</p>
            </div>
            <div>
                <p class="text-gray-500">Срок годности</p>
                <p class="font-bold {{ $inventory->expiration_date && $inventory->expiration_date->isPast() ? 'text-red-600' : '' }}">
                    {{ $inventory->expiration_date ? $inventory->expiration_date->format('d.m.Y') : '—' }}
                </p>
            </div>
        </div>
    </div>
@endsection
