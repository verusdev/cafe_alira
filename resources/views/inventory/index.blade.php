@extends('layouts.app')

@section('title', 'Запасы')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Запасы (холодильники)</h1>
        <a href="{{ route('inventory.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Добавить</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Продукт</th>
                    <th class="px-4 py-3 text-left">Холодильник</th>
                    <th class="px-4 py-3 text-left">Количество</th>
                    <th class="px-4 py-3 text-left">Годен до</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventories as $inv)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $inv->ingredient->name }}</td>
                        <td class="px-4 py-3">{{ $inv->refrigerator->name }}</td>
                        <td class="px-4 py-3">{{ $inv->quantity }} {{ $inv->ingredient->unit }}</td>
                        <td class="px-4 py-3 {{ $inv->expiration_date && $inv->expiration_date->isPast() ? 'text-red-600 font-bold' : '' }}">
                            {{ $inv->expiration_date ? $inv->expiration_date->format('d.m.Y') : '—' }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('inventory.edit', $inv) }}" class="text-yellow-500 hover:text-yellow-700 mr-2">✏️</a>
                            <form action="{{ route('inventory.destroy', $inv) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Удалить?')">🗑</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $inventories->links() }}</div>
@endsection
