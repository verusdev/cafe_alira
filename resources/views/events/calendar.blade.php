@extends('layouts.app')

@section('title', 'Календарь мероприятий')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Календарь мероприятий</h1>
        <a href="{{ route('events.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Список</a>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div id="calendar"></div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: [FullCalendar.dayGridPlugin, FullCalendar.interactionPlugin],
                initialView: 'dayGridMonth',
                locale: 'ru',
                locales: [FullCalendar.ruLocale],
                firstDay: 1,
                height: 'auto',
                editable: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,dayGridDay'
                },
                buttonText: {
                    today: 'Сегодня',
                    month: 'Месяц',
                    week: 'Неделя',
                    day: 'День'
                },
                events: '{{ route('events.calendar-data') }}',
                eventClick: function (info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault();
                    }
                },
                eventDrop: function (info) {
                    const eventId = info.event.id;
                    const newDate = info.event.startStr.slice(0, 10);

                    fetch('{{ url('/events') }}/' + eventId + '/move', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ event_date: newDate })
                    })
                    .then(function (res) {
                        if (!res.ok) {
                            info.revert();
                            alert('Ошибка при переносе');
                        }
                    })
                    .catch(function () {
                        info.revert();
                        alert('Ошибка соединения');
                    });
                },
                loading: function (isLoading) {
                    if (isLoading) {
                        document.getElementById('calendar').classList.add('opacity-50');
                    } else {
                        document.getElementById('calendar').classList.remove('opacity-50');
                    }
                }
            });
            calendar.render();
        });
    </script>
@endsection
