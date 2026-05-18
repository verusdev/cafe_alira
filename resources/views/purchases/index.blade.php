@extends('layouts.app')

@section('title', 'Закупки')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Закупки</h1>
        @if (auth()->user()->canWrite('purchases'))
            <a href="{{ route('purchases.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Новая закупка</a>
        @endif
    </div>

    <form method="GET" class="bg-white rounded-lg shadow p-4 mb-4">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Поиск</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Имя клиента" class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Статус</label>
                <select name="status" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Все</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>В ожидании</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершена</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">Найти</button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('purchases.index') }}" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400">Сбросить</a>
                @endif
            </div>
        </div>
    </form>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Дата</th>
                    <th class="px-4 py-3 text-left">Мероприятие</th>
                    <th class="px-4 py-3 text-left">Позиций</th>
                    <th class="px-4 py-3 text-left">Сумма</th>
                    <th class="px-4 py-3 text-left">Статус</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $purchase)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $purchase->purchase_date->format('d.m.Y') }}</td>
                        <td class="px-4 py-3">{{ $purchase->event->client_name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $purchase->items->count() }}</td>
                        <td class="px-4 py-3">{{ number_format($purchase->total_cost, 2) }} ₽</td>
                        <td class="px-4 py-3"><span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $purchase->status_color }}">{{ $purchase->status_label }}</span></td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('purchases.show', $purchase) }}" class="text-blue-500 hover:text-blue-700 mr-2">👁</a>
                            @if(auth()->user()->canWrite('purchases') && $purchase->status == 'pending')
                                <a href="{{ route('purchases.edit', $purchase) }}" class="text-yellow-500 hover:text-yellow-700 mr-2">✏️</a>
                                <form action="{{ route('purchases.complete', $purchase) }}" method="POST" class="inline mr-2">
                                    @csrf
                                    <button type="submit" class="text-green-500 hover:text-green-700">✅</button>
                                </form>
                                <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="inline">
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
    <div class="mt-4">{{ $purchases->links() }}</div>
@endsection
