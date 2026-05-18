@extends('layouts.app')

@section('title', 'Запасы')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Запасы (холодильники)</h1>
        @if (auth()->user()->canWrite('inventory'))
            <a href="{{ route('inventory.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Добавить</a>
        @endif
    </div>

    <form method="GET" class="bg-white rounded-lg shadow p-4 mb-4">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Поиск по продукту</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Название ингредиента" class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Холодильник</label>
                <select name="refrigerator_id" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Все</option>
                    @foreach($refrigerators as $ref)
                        <option value="{{ $ref->id }}" {{ request('refrigerator_id') == $ref->id ? 'selected' : '' }}>{{ $ref->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">Найти</button>
                @if(request()->anyFilled(['search', 'refrigerator_id']))
                    <a href="{{ route('inventory.index') }}" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400">Сбросить</a>
                @endif
            </div>
        </div>
    </form>

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
                            @if (auth()->user()->canWrite('inventory'))
                                <a href="{{ route('inventory.edit', $inv) }}" class="text-yellow-500 hover:text-yellow-700 mr-2">✏️</a>
                                <form action="{{ route('inventory.destroy', $inv) }}" method="POST" class="inline">
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
    <div class="mt-4">{{ $inventories->links() }}</div>
@endsection
