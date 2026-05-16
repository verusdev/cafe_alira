@extends('layouts.app')

@section('title', 'Уведомления')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Уведомления</h1>
        @if(auth()->user()->unreadNotifications->count())
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Прочитать все</button>
            </form>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($notifications->count())
            <div class="divide-y">
                @foreach($notifications as $notification)
                    <div class="px-4 py-4 hover:bg-gray-50 flex items-start gap-3 {{ $notification->read_at ? '' : 'bg-blue-50 font-medium' }}">
                        <span class="text-2xl">📩</span>
                        <div class="flex-1">
                            <p>
                                Новая заявка от <strong>{{ $notification->data['client_name'] }}</strong>
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $notification->data['event_type'] }},
                                {{ $notification->data['event_date'] }},
                                {{ $notification->data['people_count'] }} чел.
                            </p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('notifications.read', $notification->id) }}" class="text-blue-500 hover:text-blue-700 text-sm whitespace-nowrap">
                            Просмотреть →
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="px-4 py-8 text-center text-gray-500">Нет уведомлений</p>
        @endif
    </div>
    <div class="mt-4">{{ $notifications->links() }}</div>
@endsection
