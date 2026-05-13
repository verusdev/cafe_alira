@extends('layouts.app')

@section('title', isset($inventory) ? 'Редактировать запас' : 'Добавить запас')

@section('content')
    <h1 class="text-2xl font-bold mb-4">{{ isset($inventory) ? 'Редактировать запас' : 'Добавить запас' }}</h1>

    <form action="{{ isset($inventory) ? route('inventory.update', $inventory) : route('inventory.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @if(isset($inventory)) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Холодильник *</label>
                <select name="refrigerator_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- выберите --</option>
                    @foreach($refrigerators as $r)
                        <option value="{{ $r->id }}" {{ old('refrigerator_id', $inventory->refrigerator_id ?? request('refrigerator_id')) == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Ингредиент *</label>
                <select name="ingredient_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- выберите --</option>
                    @foreach($ingredients as $i)
                        <option value="{{ $i->id }}" {{ old('ingredient_id', $inventory->ingredient_id ?? '') == $i->id ? 'selected' : '' }}>{{ $i->name }} ({{ $i->unit }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Количество *</label>
                <input type="number" step="0.0001" name="quantity" value="{{ old('quantity', $inventory->quantity ?? 0) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Срок годности</label>
                <input type="date" name="expiration_date" value="{{ old('expiration_date', isset($inventory) && $inventory->expiration_date ? $inventory->expiration_date->format('Y-m-d') : '') }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 mt-4">
            {{ isset($inventory) ? 'Обновить' : 'Создать' }}
        </button>
    </form>
@endsection
