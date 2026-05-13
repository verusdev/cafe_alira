@extends('layouts.app')

@section('title', 'Холодильники')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Холодильники</h1>
        <a href="{{ route('refrigerators.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Добавить</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($refrigerators as $refrigerator)
            <a href="{{ route('refrigerators.show', $refrigerator) }}" class="block bg-white rounded-lg shadow p-6 hover:shadow-md">
                <h3 class="text-lg font-bold">{{ $refrigerator->name }}</h3>
                @if($refrigerator->location)
                    <p class="text-gray-500 text-sm">{{ $refrigerator->location }}</p>
                @endif
                <p class="text-sm mt-2">Продуктов: {{ $refrigerator->inventories_count }}</p>
            </a>
        @endforeach
    </div>
    <div class="mt-4">{{ $refrigerators->links() }}</div>
@endsection
