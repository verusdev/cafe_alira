@extends('layouts.app')

@section('title', isset($ingredient) ? 'Редактировать ингредиент' : 'Создать ингредиент')

@section('content')
    <h1 class="text-2xl font-bold mb-4">{{ isset($ingredient) ? 'Редактировать ингредиент' : 'Создать ингредиент' }}</h1>

    <form action="{{ isset($ingredient) ? route('ingredients.update', $ingredient) : route('ingredients.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @if(isset($ingredient)) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Название *</label>
                <input type="text" name="name" value="{{ old('name', $ingredient->name ?? '') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Единица измерения *</label>
                <select name="unit" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- выберите --</option>
                    <option value="kg" {{ old('unit', $ingredient->unit ?? '') == 'kg' ? 'selected' : '' }}>кг</option>
                    <option value="g" {{ old('unit', $ingredient->unit ?? '') == 'g' ? 'selected' : '' }}>г</option>
                    <option value="l" {{ old('unit', $ingredient->unit ?? '') == 'l' ? 'selected' : '' }}>л</option>
                    <option value="ml" {{ old('unit', $ingredient->unit ?? '') == 'ml' ? 'selected' : '' }}>мл</option>
                    <option value="pcs" {{ old('unit', $ingredient->unit ?? '') == 'pcs' ? 'selected' : '' }}>шт</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Категория *</label>
                <select name="category" class="w-full border rounded px-3 py-2" required>
                    <option value="fresh" {{ old('category', $ingredient->category ?? '') == 'fresh' ? 'selected' : '' }}>Свежие</option>
                    <option value="frozen" {{ old('category', $ingredient->category ?? '') == 'frozen' ? 'selected' : '' }}>Замороженные</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Цена за единицу</label>
                <input type="number" step="0.01" name="cost_per_unit" value="{{ old('cost_per_unit', $ingredient->cost_per_unit ?? '') }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 mt-4">
            {{ isset($ingredient) ? 'Обновить' : 'Создать' }}
        </button>
    </form>
@endsection
