@extends('layouts.app')

@section('title', isset($refrigerator) ? 'Редактировать холодильник' : 'Добавить холодильник')

@section('content')
    <h1 class="text-2xl font-bold mb-4">{{ isset($refrigerator) ? 'Редактировать холодильник' : 'Добавить холодильник' }}</h1>

    <form action="{{ isset($refrigerator) ? route('refrigerators.update', $refrigerator) : route('refrigerators.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @if(isset($refrigerator)) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Название *</label>
                <input type="text" name="name" value="{{ old('name', $refrigerator->name ?? '') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Расположение</label>
                <input type="text" name="location" value="{{ old('location', $refrigerator->location ?? '') }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium mb-1">Описание</label>
            <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description', $refrigerator->description ?? '') }}</textarea>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 mt-4">
            {{ isset($refrigerator) ? 'Обновить' : 'Создать' }}
        </button>
    </form>
@endsection
