@extends('layouts.app')

@section('title', 'Блюда')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Блюда</h1>
        @if (auth()->user()->canWrite('dishes'))
            <a href="{{ route('dishes.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Создать</a>
        @endif
    </div>

    <form method="GET" class="bg-white rounded-lg shadow p-4 mb-4">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Поиск</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Название или категория" class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Статус</label>
                <select name="is_active" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Все</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Активные</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Неактивные</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">Найти</button>
                @if(request()->anyFilled(['search', 'is_active']))
                    <a href="{{ route('dishes.index') }}" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400">Сбросить</a>
                @endif
            </div>
        </div>
    </form>

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
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($dish->hasImage())
                                    <img src="{{ $dish->image_url }}" alt="" class="w-10 h-10 object-cover rounded">
                                @endif
                                <span>{{ $dish->name }}</span>
                            </div>
                        </td>
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
                            @if (auth()->user()->canWrite('dishes'))
                                <a href="{{ route('dishes.edit', $dish) }}" class="text-yellow-500 hover:text-yellow-700 mr-2">✏️</a>
                                <form action="{{ route('dishes.destroy', $dish) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirmModal(event, this)">🗑</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $dishes->links() }}</div>
@endsection
