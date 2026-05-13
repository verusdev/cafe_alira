@extends('layouts.app')

@section('title', isset($purchase) ? 'Редактировать закупку' : 'Новая закупка')

@section('content')
    <h1 class="text-2xl font-bold mb-4">{{ isset($purchase) ? 'Редактировать закупку' : 'Новая закупка' }}</h1>

    @if($selectedEvent && $shoppingList->count())
        <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mb-4">
            <h3 class="font-bold mb-2">Рекомендовано к закупке для "{{ $selectedEvent->client_name }}"</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left">Ингредиент</th>
                        <th class="text-left">Кол-во</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shoppingList as $item)
                        <tr>
                            <td>{{ $item['ingredient']->name }}</td>
                            <td>{{ number_format($item['to_buy'], 2) }} {{ $item['ingredient']->unit }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <form action="{{ isset($purchase) ? route('purchases.update', $purchase) : route('purchases.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @if(isset($purchase)) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">Дата закупки *</label>
                <input type="date" name="purchase_date" value="{{ old('purchase_date', isset($purchase) ? $purchase->purchase_date->format('Y-m-d') : date('Y-m-d')) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Статус *</label>
                <select name="status" class="w-full border rounded px-3 py-2" required>
                    <option value="pending" {{ old('status', $purchase->status ?? '') == 'pending' ? 'selected' : '' }}>В ожидании</option>
                    <option value="completed" {{ old('status', $purchase->status ?? '') == 'completed' ? 'selected' : '' }}>Завершена</option>
                    <option value="cancelled" {{ old('status', $purchase->status ?? '') == 'cancelled' ? 'selected' : '' }}>Отменена</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Мероприятие</label>
                <select name="event_id" class="w-full border rounded px-3 py-2">
                    <option value="">— без привязки —</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ old('event_id', $purchase->event_id ?? request('event_id')) == $event->id ? 'selected' : '' }}>{{ $event->client_name }} ({{ $event->event_date->format('d.m.Y') }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Заметки</label>
            <textarea name="notes" rows="2" class="w-full border rounded px-3 py-2">{{ old('notes', $purchase->notes ?? '') }}</textarea>
        </div>

        <div class="mb-4">
            <h3 class="text-lg font-bold mb-2">Позиции закупки</h3>
            <table class="w-full" id="items-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Ингредиент</th>
                        <th class="px-4 py-2 text-left">Количество</th>
                        <th class="px-4 py-2 text-left">Стоимость</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($purchase))
                        @foreach($purchase->items as $item)
                            <tr class="border-t">
                                <td class="px-4 py-2">
                                    <select name="items[{{ $loop->index }}][ingredient_id]" class="w-full border rounded px-3 py-2">
                                        <option value="">-- выберите --</option>
                                        @foreach($ingredients as $i)
                                            <option value="{{ $i->id }}" {{ $i->id == $item->ingredient_id ? 'selected' : '' }}>{{ $i->name }} ({{ $i->unit }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" step="0.0001" name="items[{{ $loop->index }}][quantity]" value="{{ $item->quantity }}" class="w-full border rounded px-3 py-2">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" step="0.01" name="items[{{ $loop->index }}][cost]" value="{{ $item->cost }}" class="w-full border rounded px-3 py-2">
                                </td>
                                <td class="px-4 py-2"><button type="button" class="text-red-500 remove-row">Удалить</button></td>
                            </tr>
                        @endforeach
                    @elseif($selectedEvent && $shoppingList->count())
                        @foreach($shoppingList as $idx => $item)
                            <tr class="border-t">
                                <td class="px-4 py-2">
                                    <select name="items[{{ $idx }}][ingredient_id]" class="w-full border rounded px-3 py-2">
                                        <option value="">-- выберите --</option>
                                        @foreach($ingredients as $i)
                                            <option value="{{ $i->id }}" {{ $i->id == $item['ingredient']->id ? 'selected' : '' }}>{{ $i->name }} ({{ $i->unit }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" step="0.0001" name="items[{{ $idx }}][quantity]" value="{{ $item['to_buy'] }}" class="w-full border rounded px-3 py-2">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" step="0.01" name="items[{{ $idx }}][cost]" value="" class="w-full border rounded px-3 py-2">
                                </td>
                                <td class="px-4 py-2"><button type="button" class="text-red-500 remove-row">Удалить</button></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <button type="button" id="add-item" class="mt-2 text-blue-500 hover:text-blue-700">+ Добавить позицию</button>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
            {{ isset($purchase) ? 'Обновить' : 'Создать' }}
        </button>
    </form>
@endsection

@section('scripts')
<script>
    let itemIndex = {{ isset($purchase) ? $purchase->items->count() : ($selectedEvent ? $shoppingList->count() : 0) }};
    const allIngredients = @json($ingredients);

    document.getElementById('add-item').addEventListener('click', function() {
        const tbody = document.querySelector('#items-table tbody');
        const row = document.createElement('tr');
        row.className = 'border-t';
        row.innerHTML = `
            <td class="px-4 py-2">
                <select name="items[${itemIndex}][ingredient_id]" class="w-full border rounded px-3 py-2">
                    <option value="">-- выберите --</option>
                    ${allIngredients.map(i => `<option value="${i.id}">${i.name} (${i.unit})</option>`).join('')}
                </select>
            </td>
            <td class="px-4 py-2">
                <input type="number" step="0.0001" name="items[${itemIndex}][quantity]" class="w-full border rounded px-3 py-2">
            </td>
            <td class="px-4 py-2">
                <input type="number" step="0.01" name="items[${itemIndex}][cost]" class="w-full border rounded px-3 py-2">
            </td>
            <td class="px-4 py-2"><button type="button" class="text-red-500 remove-row">Удалить</button></td>
        `;
        tbody.appendChild(row);
        itemIndex++;
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
