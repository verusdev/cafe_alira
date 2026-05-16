<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Меню — {{ $event->client_name }}</title>
    @vite('resources/css/app.css')
    <style>
        @page { margin: 15mm; }
        @media print {
            body { font-size: 11pt; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body class="p-8">
    <div class="max-w-4xl mx-auto">
        <div class="no-print mb-6">
            <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">🖨 Печать</button>
            <a href="{{ route('events.show', $event) }}" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Назад</a>
        </div>

        <div class="text-center mb-8 pb-6 border-b-2 border-gray-300">
            <h1 class="text-3xl font-bold mb-2">{{ $event->client_name }}</h1>
            <p class="text-xl text-gray-600">{{ $event->type_label }}</p>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-8">
            <div>
                <p class="text-sm text-gray-500">Дата</p>
                <p class="font-bold text-lg">{{ $event->event_date->format('d.m.Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Время</p>
                <p class="font-bold text-lg">{{ $event->event_time ? date('H:i', strtotime($event->event_time)) : '—' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Количество гостей</p>
                <p class="font-bold text-lg">{{ $event->people_count }} чел.</p>
            </div>
        </div>

        @if($event->dishes->count())
            <h2 class="text-2xl font-bold mb-4">Меню</h2>
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-800">
                        <th class="py-2 text-left">Блюдо</th>
                        <th class="py-2 text-right">Порций</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($event->dishes as $dish)
                        <tr class="border-b border-gray-300">
                            <td class="py-3 text-lg">{{ $dish->name }}</td>
                            <td class="py-3 text-right text-lg font-bold">{{ $dish->pivot->servings }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500 text-lg">Меню не составлено</p>
            </div>
        @endif

        @if($event->notes)
            <div class="mt-8 pt-6 border-t border-gray-300">
                <h3 class="text-sm text-gray-500 mb-2">Заметки</h3>
                <p class="text-gray-700">{{ $event->notes }}</p>
            </div>
        @endif

        <div class="text-center mt-12 text-sm text-gray-400">
            {{ date('d.m.Y H:i') }}
        </div>
    </div>
</body>
</html>