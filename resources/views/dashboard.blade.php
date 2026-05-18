@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm">Блюда</h3>
            <p class="text-3xl font-bold">{{ $stats['dishes_count'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm">Ингредиенты</h3>
            <p class="text-3xl font-bold">{{ $stats['ingredients_count'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm">Активные мероприятия</h3>
            <p class="text-3xl font-bold">{{ $stats['active_events'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm">Всего мероприятий</h3>
            <p class="text-3xl font-bold">{{ $stats['total_events'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Ближайшие мероприятия</h2>
            @if($stats['upcoming_events']->count())
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="pb-2">Клиент</th>
                            <th class="pb-2">Тип</th>
                            <th class="pb-2">Дата</th>
                            <th class="pb-2">Гостей</th>
                            <th class="pb-2">Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['upcoming_events'] as $event)
                            <tr class="border-t">
                                <td class="py-2">{{ $event->client_name }}</td>
                                <td class="py-2">{{ $event->type_label }}</td>
                                <td class="py-2">{{ $event->event_date->format('d.m.Y') }}</td>
                                <td class="py-2">{{ $event->people_count }}</td>
                                <td class="py-2"><span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $event->status_color }}">{{ $event->status_label }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500">Нет ближайших мероприятий</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Последние закупки</h2>
            @if($stats['recent_purchases']->count())
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="pb-2">Дата</th>
                            <th class="pb-2">Сумма</th>
                            <th class="pb-2">Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['recent_purchases'] as $purchase)
                            <tr class="border-t">
                                <td class="py-2">{{ $purchase->purchase_date->format('d.m.Y') }}</td>
                                <td class="py-2">{{ number_format($purchase->total_cost, 2) }} ₽</td>
                                <td class="py-2"><span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $purchase->status_color }}">{{ $purchase->status_label }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500">Нет закупок</p>
            @endif
        </div>
    </div>

    @if(auth()->user()->isManager())
        <div class="bg-white rounded-lg shadow p-6 mt-6 border-2 border-yellow-400">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Финансовая сводка</h2>
                <a href="{{ route('export.finance') }}" class="bg-green-600 text-white px-3 py-1.5 rounded text-sm hover:bg-green-700">📥 Excel</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-gray-500 text-sm">Общая выручка (активные)</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_revenue'], 2, ',', ' ') }} ₽</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Затраты на продукты (активные)</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['total_ingredient_cost'], 2, ',', ' ') }} ₽</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Ожидаемая прибыль</p>
                    <p class="text-2xl font-bold {{ $stats['expected_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($stats['expected_profit'], 2, ',', ' ') }} ₽
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Рентабельность</p>
                    <p class="text-2xl font-bold {{ $stats['profit_margin'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($stats['profit_margin'], 1) }}%
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Выручка по месяцам</h2>
            <canvas id="revenueChart" height="200"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Типы мероприятий</h2>
            <canvas id="typeChart" height="200"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Статусы мероприятий</h2>
            <canvas id="statusChart" height="200"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Количество мероприятий по месяцам</h2>
            <canvas id="countChart" height="200"></canvas>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/js/app.js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const blue = 'rgba(59, 130, 246, 0.7)';
            const green = 'rgba(16, 185, 129, 0.7)';
            const orange = 'rgba(249, 115, 22, 0.7)';
            const purple = 'rgba(139, 92, 246, 0.7)';
            const colors = [blue, green, orange, purple, 'rgba(236, 72, 153, 0.7)', 'rgba(107, 114, 128, 0.7)'];

            const monthlyData = @json($monthlyRevenue);
            const labels = monthlyData.map(function (r) {
                const parts = r.month.split('-');
                const months = ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];
                return months[parseInt(parts[1]) - 1] + ' ' + parts[0];
            });

            new Chart(document.getElementById('revenueChart'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Выручка, ₽',
                        data: monthlyData.map(function (r) { return r.revenue; }),
                        backgroundColor: blue
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });

            new Chart(document.getElementById('countChart'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Мероприятий',
                        data: monthlyData.map(function (r) { return r.count; }),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });

            const typeData = @json($typeStats);
            new Chart(document.getElementById('typeChart'), {
                type: 'doughnut',
                data: {
                    labels: typeData.map(function (r) { return r.label; }),
                    datasets: [{
                        data: typeData.map(function (r) { return r.count; }),
                        backgroundColor: colors
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });

            const statusData = @json($statusStats);
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: statusData.map(function (r) { return r.label; }),
                    datasets: [{
                        data: statusData.map(function (r) { return r.count; }),
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#6b7280', '#ef4444']
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });
        });
    </script>
@endsection
