<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Страница не найдена</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="text-center max-w-md mx-auto p-8">
        <div class="text-8xl font-bold text-yellow-500 mb-4">404</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Страница не найдена</h1>
        <p class="text-gray-500 mb-6">Запрашиваемая страница не существует или была удалена.</p>
        <a href="{{ route('dashboard') }}" class="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">На главную</a>
    </div>
</body>
</html>