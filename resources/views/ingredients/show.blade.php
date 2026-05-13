@extends('layouts.app')

@section('title', $ingredient->name)

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">{{ $ingredient->name }}</h1>
        <div>
            <a href="{{ route('ingredients.edit', $ingredient) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Редактировать</a>
            <a href="{{ route('ingredients.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Назад</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500">Единица измерения</p>
            <p class="font-bold">{{ $ingredient->unit }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500">Категория</p>
            <p class="font-bold">{{ $ingredient->category == 'frozen' ? 'Замороженный' : 'Свежий' }}</p>
        </div>
        @if($ingredient->cost_per_unit)
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500">Цена за единицу</p>
            <p class="font-bold">{{ number_format($ingredient->cost_per_unit, 2) }} ₽</p>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Используется в блюдах</h2>
            @if($ingredient->dishes->count())
                <ul class="list-disc pl-5">
                    @foreach($ingredient->dishes as $dish)
                        <li><a href="{{ route('dishes.show', $dish) }}" class="text-blue-500">{{ $dish->name }}</a></li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">Не используется</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">На складе (холодильники)</h2>
            @if($ingredient->inventories->count())
                <ul class="list-disc pl-5">
                    @foreach($ingredient->inventories as $inv)
                        <li>{{ $inv->refrigerator->name }}: {{ $inv->quantity }} {{ $ingredient->unit }}
                            @if($inv->expiration_date) (годен до {{ $inv->expiration_date->format('d.m.Y') }}) @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">Нет в наличии</p>
            @endif
        </div>
    </div>
@endsection
