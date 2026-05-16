@extends('layouts.app')

@section('title', 'Мероприятия')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Мероприятия</h1>
        @if (auth()->user()->canWrite('events'))
            <a href="{{ route('events.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Создать</a>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Клиент</th>
                    <th class="px-4 py-3 text-left">Тип</th>
                    <th class="px-4 py-3 text-left">Дата</th>
                    <th class="px-4 py-3 text-left">Гостей</th>
                    <th class="px-4 py-3 text-left">Блюд</th>
                    <th class="px-4 py-3 text-left">Статус</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $event->client_name }}</td>
                        <td class="px-4 py-3">{{ $event->type_label }}</td>
                        <td class="px-4 py-3">{{ $event->event_date->format('d.m.Y') }}</td>
                        <td class="px-4 py-3">{{ $event->people_count }}</td>
                        <td class="px-4 py-3">{{ $event->dishes->count() }}</td>
                        <td class="px-4 py-3"><span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $event->status_color }}">{{ $event->status_label }}</span></td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('events.show', $event) }}" class="text-blue-500 hover:text-blue-700 mr-2">👁</a>
                            @if (auth()->user()->canWrite('events'))
                                <a href="{{ route('events.edit', $event) }}" class="text-yellow-500 hover:text-yellow-700 mr-2">✏️</a>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Удалить?')">🗑</button>
                                </form>
                            @endif
                            <a href="{{ route('events.shopping-list', $event) }}" class="text-green-500 hover:text-green-700 mr-2">🛒</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $events->links() }}</div>
@endsection
