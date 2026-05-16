<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список закупок — {{ $event->client_name }}</title>
    @vite('resources/css/app.css')
    <style>
        @page { margin: 15mm; }
        @media print {
            body { font-size: 11pt; }
            .no-print { display: none !important; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; }
        }
        .check-item { cursor: pointer; }
        .check-item.checked td { text-decoration: line-through; color: #9ca3af; }
    </style>
</head>
<body class="p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-start mb-6 no-print">
            <div>
                <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">🖨 Печать</button>
                <a href="{{ route('events.shopping-list', $event) }}" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Назад</a>
            </div>
        </div>

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold">Список закупок</h1>
            <p class="text-gray-600">{{ $event->client_name }} — {{ $event->event_date->format('d.m.Y') }} ({{ $event->type_label }})</p>
        </div>

        @if($shoppingList->count())
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b-2 border-gray-800">
                        <th class="py-2 text-left w-8"></th>
                        <th class="py-2 text-left">Ингредиент</th>
                        <th class="py-2 text-right">Кол-во</th>
                        <th class="py-2 text-right">Ед.</th>
                        <th class="py-2 text-right">Категория</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shoppingList as $item)
                        <tr class="border-b border-gray-300 check-item" onclick="this.classList.toggle('checked')">
                            <td class="py-2 text-center"><input type="checkbox" class="w-4 h-4"></td>
                            <td class="py-2">{{ $item['ingredient']->name }}</td>
                            <td class="py-2 text-right font-bold">{{ number_format($item['to_buy'], 2) }}</td>
                            <td class="py-2 text-right">{{ $item['ingredient']->unit }}</td>
                            <td class="py-2 text-right">{{ $item['ingredient']->category == 'frozen' ? 'Заморозка' : 'Свежие' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="text-xs text-gray-400 mt-4 text-center">Клик по строке отмечает купленное</p>
        @else
            <p class="text-gray-500 text-center py-8">Все продукты есть в наличии. Закупки не требуются.</p>
        @endif
    </div>

    <script>
        document.querySelectorAll('.check-item').forEach(row => {
            row.querySelector('input[type=checkbox]').addEventListener('change', function() {
                row.classList.toggle('checked', this.checked);
            });
        });
    </script>
</body>
</html>