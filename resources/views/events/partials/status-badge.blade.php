@php
    $colors = [
        'new' => 'bg-blue-100 text-blue-800',
        'confirmed' => 'bg-green-100 text-green-800',
        'in_progress' => 'bg-yellow-100 text-yellow-800',
        'completed' => 'bg-gray-100 text-gray-800',
        'cancelled' => 'bg-red-100 text-red-800',
    ];
    $labels = [
        'new' => 'Новый',
        'confirmed' => 'Подтверждён',
        'in_progress' => 'В работе',
        'completed' => 'Завершён',
        'cancelled' => 'Отменён',
    ];
@endphp
<span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $colors[$status] ?? 'bg-gray-100 text-gray-800' }}">{{ $labels[$status] ?? $status }}</span>