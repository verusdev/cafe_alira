@extends('layouts.app')

@section('title', 'Ингредиенты')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Ингредиенты</h1>
        <a href="{{ route('ingredients.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Создать</a>
    </div>

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
                            <a href="{{ route('ingredients.edit', $ingredient) }}" class="text-yellow-500 hover:text-yellow-700 mr-2">✏️</a>
                            <form action="{{ route('ingredients.destroy', $ingredient) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Удалить?')">🗑</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $ingredients->links() }}</div>
@endsection
