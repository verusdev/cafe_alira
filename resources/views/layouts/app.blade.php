<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CRM Кафе')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex space-x-4 items-center">
                    <a href="{{ route('dashboard') }}" class="text-lg font-bold text-gray-800">CRM Кафе</a>
                    <a href="{{ route('events.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Мероприятия</a>
                    <a href="{{ route('events.calendar') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Календарь</a>
                    @if (auth()->user()->canWrite('dishes'))
                        <a href="{{ route('dishes.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Блюда</a>
                    @endif
                    @if (auth()->user()->canWrite('ingredients'))
                        <a href="{{ route('ingredients.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Ингредиенты</a>
                    @endif
                    @if (auth()->user()->canWrite('refrigerators'))
                        <a href="{{ route('refrigerators.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Холодильники</a>
                    @endif
                    @if (auth()->user()->canWrite('inventory'))
                        <a href="{{ route('inventory.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Запасы</a>
                    @endif
                    @if (auth()->user()->canWrite('purchases'))
                        <a href="{{ route('purchases.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Закупки</a>
                    @endif
                </div>
                <div class="flex items-center space-x-4">
                    @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                    <a href="{{ route('notifications.index') }}" class="relative text-gray-500 hover:text-gray-700">
                        🔔
                        @if($unread)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">{{ $unread > 9 ? '9+' : $unread }}</span>
                        @endif
                    </a>
                    <span class="text-sm text-gray-500">{{ auth()->user()->name }} ({{ auth()->user()->roleLabel() }})</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700">Выйти</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4">
        @if (session('success'))
            <div id="flash-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            <script>setTimeout(() => { const el = document.getElementById('flash-success'); if (el) el.remove(); }, 3000);</script>
        @endif
        @yield('content')
    </main>
    @yield('scripts')
    <x-confirm-modal />
    <script>
        document.addEventListener('submit', function (e) {
            const btn = e.target.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = btn.dataset.loading || 'Отправка...';
            }
        });
    </script>
</body>
</html>
