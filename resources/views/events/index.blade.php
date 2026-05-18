@extends('layouts.app')

@section('title', 'Мероприятия')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Мероприятия</h1>
        <div class="flex gap-2">
            @if (auth()->user()->canWrite('events'))
                <a href="{{ route('events.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Создать</a>
            @endif
            @if (auth()->user()->isManager())
                <a href="{{ route('export.events') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">📥 Excel</a>
            @endif
        </div>
    </div>

    <form method="GET" class="bg-white rounded-lg shadow p-4 mb-4">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Поиск</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Имя или телефон" class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Статус</label>
                <select name="status" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Все</option>
                    @foreach(array_keys(\App\Models\Event::STATUSES) as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ \App\Models\Event::STATUSES[$st] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Тип</label>
                <select name="event_type" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Все</option>
                    @foreach($eventTypes as $key => $type)
                        <option value="{{ $key }}" {{ request('event_type') == $key ? 'selected' : '' }}>{{ $type['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Дата с</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Дата по</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border rounded px-3 py-2 text-sm">
            </div>
        </div>
        <div class="flex gap-2 mt-3">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">Найти</button>
            @if(request()->anyFilled(['search', 'status', 'event_type', 'date_from', 'date_to']))
                <a href="{{ route('events.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400">Сбросить</a>
            @endif
        </div>
    </form>

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
                        <td class="px-4 py-3">
                            {{ $event->client_name }}
                            @if($event->client_phone)
                                <a href="{{ route('events.create', ['phone' => $event->client_phone]) }}"
                                   class="text-xs text-blue-500 hover:text-blue-700 ml-1"
                                   title="Новое мероприятие для {{ $event->client_phone }}">🔄</a>
                            @elseif($event->client_name)
                                <a href="{{ route('events.create', ['client_name' => $event->client_name]) }}"
                                   class="text-xs text-blue-500 hover:text-blue-700 ml-1"
                                   title="Новое мероприятие для {{ $event->client_name }}">🔄</a>
                            @endif
                        </td>
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
                                    <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirmModal(event, this)">🗑</button>
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
