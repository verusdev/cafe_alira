@extends('layouts.app')

@section('title', isset($dish) ? 'Редактировать блюдо' : 'Создать блюдо')

@section('content')
    <h1 class="text-2xl font-bold mb-4">{{ isset($dish) ? 'Редактировать блюдо' : 'Создать блюдо' }}</h1>

    <form action="{{ isset($dish) ? route('dishes.update', $dish) : route('dishes.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf
        @if(isset($dish)) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">Название *</label>
                <input type="text" name="name" value="{{ old('name', $dish->name ?? '') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Категория</label>
                <input type="text" name="category" value="{{ old('category', $dish->category ?? '') }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Цена на человека *</label>
                <input type="number" step="0.01" name="price_per_person" value="{{ old('price_per_person', $dish->price_per_person ?? 0) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="flex items-center">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $dish->is_active ?? true) ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm font-medium">Активно</span>
                </label>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Фото</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full border rounded px-3 py-2">
                @if(isset($dish) && $dish->hasImage())
                    <div class="mt-2">
                        <img src="{{ $dish->image_url }}" alt="{{ $dish->name }}" class="w-32 h-32 object-cover rounded border">
                    </div>
                @endif
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Описание</label>
            <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description', $dish->description ?? '') }}</textarea>
        </div>

        <div class="mb-4">
            <h3 class="text-lg font-bold mb-2">Ингредиенты (на 1 порцию)</h3>
            <table class="w-full" id="ingredients-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Ингредиент</th>
                        <th class="px-4 py-2 text-left">Количество</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($dish))
                        @foreach($dish->ingredients as $ingredient)
                            <tr class="border-t">
                                <td class="px-4 py-2">
                                    <select name="ingredients[{{ $loop->index }}][id]" class="w-full border rounded px-3 py-2">
                                        <option value="">-- выберите --</option>
                                        @foreach($ingredients as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == $ingredient->id ? 'selected' : '' }}>{{ $item->name }} ({{ $item->unit }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" step="0.0001" name="ingredients[{{ $loop->index }}][quantity]" value="{{ $ingredient->pivot->quantity_per_person }}" class="w-full border rounded px-3 py-2">
                                </td>
                                <td class="px-4 py-2"><button type="button" class="text-red-500 remove-row">Удалить</button></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <button type="button" id="add-ingredient" class="mt-2 text-blue-500 hover:text-blue-700">+ Добавить ингредиент</button>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
            {{ isset($dish) ? 'Обновить' : 'Создать' }}
        </button>
    </form>
@endsection

@section('scripts')
<script>
    let ingredientIndex = {{ isset($dish) ? $dish->ingredients->count() : 0 }};
    const ingredients = @json($ingredients);

    document.getElementById('add-ingredient').addEventListener('click', function() {
        const tbody = document.querySelector('#ingredients-table tbody');
        const row = document.createElement('tr');
        row.className = 'border-t';
        row.innerHTML = `
            <td class="px-4 py-2">
                <select name="ingredients[${ingredientIndex}][id]" class="w-full border rounded px-3 py-2">
                    <option value="">-- выберите --</option>
                    ${ingredients.map(i => `<option value="${i.id}">${i.name} (${i.unit})</option>`).join('')}
                </select>
            </td>
            <td class="px-4 py-2">
                <input type="number" step="0.0001" name="ingredients[${ingredientIndex}][quantity]" class="w-full border rounded px-3 py-2">
            </td>
            <td class="px-4 py-2"><button type="button" class="text-red-500 remove-row">Удалить</button></td>
        `;
        tbody.appendChild(row);
        ingredientIndex++;
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
