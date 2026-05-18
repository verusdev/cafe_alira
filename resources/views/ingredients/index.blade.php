@extends('layouts.app')

@section('title', 'Ингредиенты')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Ингредиенты</h1>
        @if (auth()->user()->canWrite('ingredients'))
            <a href="{{ route('ingredients.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Создать</a>
        @endif
    </div>

    <form method="GET" class="bg-white rounded-lg shadow p-4 mb-4">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Поиск</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Название или категория" class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">Найти</button>
                @if(request('search'))
                    <a href="{{ route('ingredients.index') }}" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400">Сбросить</a>
                @endif
            </div>
        </div>
    </form>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Название</th>
                    <th class="px-4 py-3 text-left">Ед. изм.</th>
                    <th class="px-4 py-3 text-left">Категория</th>
                    <th class="px-4 py-3 text-left">Цена</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($ingredients as $ingredient)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $ingredient->name }}</td>
                        <td class="px-4 py-3">{{ $ingredient->unit }}</td>
                        <td class="px-4 py-3">{{ $ingredient->category }}</td>
                        <td class="px-4 py-3">{{ $ingredient->cost_per_unit ? number_format($ingredient->cost_per_unit, 2) . ' ₽' : '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('ingredients.show', $ingredient) }}" class="text-blue-500 hover:text-blue-700 mr-2">👁</a>
                            @if (auth()->user()->canWrite('ingredients'))
                                <a href="{{ route('ingredients.edit', $ingredient) }}" class="text-yellow-500 hover:text-yellow-700 mr-2">✏️</a>
                                <form action="{{ route('ingredients.destroy', $ingredient) }}" method="POST" class="inline">
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
    <div class="mt-4">{{ $ingredients->links() }}</div>
@endsection
