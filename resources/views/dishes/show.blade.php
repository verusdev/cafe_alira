@extends('layouts.app')

@section('title', $dish->name)

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">{{ $dish->name }}</h1>
        <div>
            @if (auth()->user()->canWrite('dishes'))
                <a href="{{ route('dishes.edit', $dish) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Редактировать</a>
            @endif
            <a href="{{ route('dishes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Назад</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-4">
        @if($dish->hasImage())
            <div class="mb-4">
                <img src="{{ $dish->image_url }}" alt="{{ $dish->name }}" class="w-64 h-64 object-cover rounded-lg shadow">
            </div>
        @endif
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-gray-500">Категория</p>
                <p class="font-bold">{{ $dish->category ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Цена на человека</p>
                <p class="font-bold">{{ number_format($dish->price_per_person, 2) }} ₽</p>
            </div>
            <div>
                <p class="text-gray-500">Статус</p>
                <p class="font-bold {{ $dish->is_active ? 'text-green-600' : 'text-red-600' }}">{{ $dish->is_active ? 'Активно' : 'Неактивно' }}</p>
            </div>
        </div>
        @if($dish->description)
            <div class="mt-4">
                <p class="text-gray-500">Описание</p>
                <p>{{ $dish->description }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Ингредиенты (на 1 порцию)</h2>
        @if($dish->ingredients->count())
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Ингредиент</th>
                        <th class="px-4 py-3 text-left">Количество</th>
                        <th class="px-4 py-3 text-left">Ед. изм.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dish->ingredients as $ingredient)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $ingredient->name }}</td>
                            <td class="px-4 py-3">{{ $ingredient->pivot->quantity_per_person }}</td>
                            <td class="px-4 py-3">{{ $ingredient->unit }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Нет ингредиентов</p>
        @endif
    </div>
@endsection
