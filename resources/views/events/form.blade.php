@extends('layouts.app')

@section('title', isset($event) ? 'Редактировать мероприятие' : 'Создать мероприятие')

@section('content')
    <h1 class="text-2xl font-bold mb-4">{{ isset($event) ? 'Редактировать мероприятие' : 'Создать мероприятие' }}</h1>

    @if(isset($previousEvents) && $previousEvents->isNotEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700 font-medium">Клиент уже обращался ранее</p>
                    <ul class="mt-1 text-sm text-yellow-600 list-disc list-inside">
                        @foreach($previousEvents as $prev)
                            <li>
                                <a href="{{ route('events.show', $prev) }}" class="underline hover:text-yellow-800">
                                    {{ $prev->client_name }} — {{ $prev->event_date->format('d.m.Y') }} ({{ $prev->type_label }})
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ isset($event) ? route('events.update', $event) : route('events.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @if(isset($event)) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">Имя клиента *</label>
                <input type="text" name="client_name" value="{{ old('client_name', $event->client_name ?? '') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Телефон</label>
                <input type="text" name="client_phone" value="{{ old('client_phone', $event->client_phone ?? '') }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="client_email" value="{{ old('client_email', $event->client_email ?? '') }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Тип мероприятия *</label>
                <select name="event_type" class="w-full border rounded px-3 py-2" required>
                    @foreach($eventTypes as $key => $type)
                        <option value="{{ $key }}" {{ old('event_type', $event->event_type ?? '') == $key ? 'selected' : '' }}>
                            {{ $type['label'] }} ({{ number_format($type['price_per_person'], 0, ',', ' ') }} ₽/чел)
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Статус *</label>
                <select name="status" class="w-full border rounded px-3 py-2" required>
                    @if(isset($event))
                        @foreach($allowedStatuses as $s)
                            @isset(\App\Models\Event::STATUSES[$s])
                                <option value="{{ $s }}" {{ old('status', $event->status) == $s ? 'selected' : '' }}>{{ \App\Models\Event::STATUSES[$s] }}</option>
                            @endisset
                        @endforeach
                    @else
                        <option value="new" selected>Новый</option>
                    @endif
                </select>
                @if(isset($event) && count($allowedStatuses) <= 1)
                    <p class="text-gray-400 text-xs mt-1">Статус нельзя изменить</p>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Дата мероприятия *</label>
                <input type="date" name="event_date" value="{{ old('event_date', isset($event) ? $event->event_date->format('Y-m-d') : '') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Время</label>
                <input type="time" name="event_time" value="{{ old('event_time', isset($event) && $event->event_time ? date('H:i', strtotime($event->event_time)) : '') }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Количество человек *</label>
                <input type="number" name="people_count" value="{{ old('people_count', $event->people_count ?? 1) }}" class="w-full border rounded px-3 py-2" required min="1">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Заметки</label>
            <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2">{{ old('notes', $event->notes ?? '') }}</textarea>
        </div>

        <div class="mb-4">
            <h3 class="text-lg font-bold mb-2">Блюда</h3>
            <table class="w-full" id="dishes-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Блюдо</th>
                        <th class="px-4 py-2 text-left">Порций</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($event))
                        @foreach($event->dishes as $dish)
                            <tr class="border-t">
                                <td class="px-4 py-2">
                                    <select name="dishes[{{ $loop->index }}][id]" class="w-full border rounded px-3 py-2">
                                        <option value="">-- выберите --</option>
                                        @foreach($dishes as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == $dish->id ? 'selected' : '' }}>{{ $item->name }} ({{ number_format($item->price_per_person, 2) }} ₽/чел)</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" name="dishes[{{ $loop->index }}][servings]" value="{{ $dish->pivot->servings }}" class="w-full border rounded px-3 py-2" min="0">
                                </td>
                                <td class="px-4 py-2"><button type="button" class="text-red-500 remove-row">Удалить</button></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <button type="button" id="add-dish" class="mt-2 text-blue-500 hover:text-blue-700">+ Добавить блюдо</button>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
            {{ isset($event) ? 'Обновить' : 'Создать' }}
        </button>
    </form>
@endsection

@section('scripts')
<script>
    let dishIndex = {{ isset($event) ? $event->dishes->count() : 0 }};
    const dishes = @json($dishes);

    document.getElementById('add-dish').addEventListener('click', function() {
        const tbody = document.querySelector('#dishes-table tbody');
        const row = document.createElement('tr');
        row.className = 'border-t';
        row.innerHTML = `
            <td class="px-4 py-2">
                <select name="dishes[${dishIndex}][id]" class="w-full border rounded px-3 py-2">
                    <option value="">-- выберите --</option>
                    ${dishes.map(d => `<option value="${d.id}">${d.name} (${(d.price_per_person/1).toFixed(2)} ₽/чел)</option>`).join('')}
                </select>
            </td>
            <td class="px-4 py-2">
                <input type="number" name="dishes[${dishIndex}][servings]" class="w-full border rounded px-3 py-2" min="0">
            </td>
            <td class="px-4 py-2"><button type="button" class="text-red-500 remove-row">Удалить</button></td>
        `;
        tbody.appendChild(row);
        dishIndex++;
        attachRemoveHandlers();
    });

    function attachRemoveHandlers() {
        document.querySelectorAll('.remove-row').forEach(btn => {
            btn.removeEventListener('click', handleRemove);
            btn.addEventListener('click', handleRemove);
        });
    }

    function handleRemove(e) {
        e.target.closest('tr').remove();
    }

    attachRemoveHandlers();
</script>
@endsection
