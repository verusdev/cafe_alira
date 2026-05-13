@extends('layouts.app')

@section('title', 'Блюда')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Блюда</h1>
        <a href="{{ route('dishes.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Создать</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Название</th>
                    <th class="px-4 py-3 text-left">Категория</th>
                    <th class="px-4 py-3 text-left">Цена/чел</th>
                    <th class="px-4 py-3 text-left">Ингредиентов</th>
                    <th class="px-4 py-3 text-left">Статус</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($dishes as $dish)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $dish->name }}</td>
                        <td class="px-4 py-3">{{ $dish->category }}</td>
                        <td class="px-4 py-3">{{ number_format($dish->price_per_person, 2) }} ₽</td>
                        <td class="px-4 py-3">{{ $dish->ingredients->count() }}</td>
                        <td class="px-4 py-3">
                            <span class="{{ $dish->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $dish->is_active ? 'Активно' : 'Неактивно' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('dishes.show', $dish) }}" class="text-blue-500 hover:text-blue-700 mr-2">👁</a>
                            <a href="{{ route('dishes.edit', $dish) }}" class="text-yellow-500 hover:text-yellow-700 mr-2">✏️</a>
                            <form action="{{ route('dishes.destroy', $dish) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Удалить?')">🗑</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $dishes->links() }}</div>
@endsection
