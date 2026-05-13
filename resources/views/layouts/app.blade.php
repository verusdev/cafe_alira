<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CRM Кафе')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex space-x-4 items-center">
                    <a href="{{ route('dashboard') }}" class="text-lg font-bold text-gray-800">CRM Кафе</a>
                    <a href="{{ route('dishes.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Блюда</a>
                    <a href="{{ route('ingredients.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Ингредиенты</a>
                    <a href="{{ route('events.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Мероприятия</a>
                    <a href="{{ route('events.calendar') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Календарь</a>
                    <a href="{{ route('refrigerators.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Холодильники</a>
                    <a href="{{ route('inventory.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Запасы</a>
                    <a href="{{ route('purchases.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Закупки</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </main>
    @yield('scripts')
</body>
</html>
