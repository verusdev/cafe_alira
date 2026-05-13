<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кафе — банкеты и мероприятия</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased">
    <nav class="bg-white/80 backdrop-blur-md shadow-sm fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <span class="text-xl font-bold text-orange-600">☕ Кафе</span>
            <div class="flex items-center gap-6">
                <a href="#services" class="text-gray-600 hover:text-orange-600">Услуги</a>
                <a href="#pricing" class="text-gray-600 hover:text-orange-600">Цены</a>
                <a href="#contact" class="text-gray-600 hover:text-orange-600">Контакты</a>
                <a href="{{ route('login') }}" class="bg-orange-500 text-white px-4 py-2 rounded-full text-sm hover:bg-orange-600 transition">Войти</a>
            </div>
        </div>
    </nav>

    <section class="relative pt-16 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-400 via-red-400 to-pink-500 opacity-90"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMiIvPjwvZz48L2c+PC9zdmc+')] opacity-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 py-32 md:py-48">
            <div class="text-center">
                <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-6 leading-tight">
                    Ваше идеальное<br>
                    <span class="text-yellow-300">мероприятие</span>
                </h1>
                <p class="text-xl md:text-2xl text-white/90 mb-10 max-w-2xl mx-auto">
                    Организуйте банкет, свадьбу или корпоратив в уютной атмосфере. Вкусная кухня, профессиональный сервис и индивидуальный подход.
                </p>
                <a href="#contact" class="inline-block bg-white text-orange-600 font-bold px-10 py-4 rounded-full text-lg shadow-2xl hover:bg-yellow-50 transform hover:scale-105 transition-all">
                    Оставить заявку
                </a>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <section id="services" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-4">Наши услуги</h2>
            <p class="text-gray-500 text-center mb-12 max-w-xl mx-auto">Всё, что нужно для проведения незабываемого мероприятия</p>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">🍽️</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Банкетное меню</h3>
                    <p class="text-gray-500">Разнообразное меню от шеф-повара. Индивидуальный подбор блюд под любой формат мероприятия.</p>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">🎉</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Организация праздников</h3>
                    <p class="text-gray-500">Дни рождения, юбилеи, свадьбы, корпоративы — поможем с организацией мероприятия любого масштаба.</p>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">🚚</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Доставка блюд</h3>
                    <p class="text-gray-500">Доставка готовых блюд к вашему столу. Свежая кухня, удобная упаковка, быстрая подача.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-4">Стоимость</h2>
            <p class="text-gray-500 text-center mb-12 max-w-xl mx-auto">Выберите формат мероприятия — базовая цена от 800 до 3 000 ₽ за человека</p>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($eventTypes as $key => $type)
                    <div class="rounded-2xl shadow-lg p-8 border-2 {{ $key === 'wedding' ? 'border-orange-400 bg-orange-50 scale-105' : 'border-gray-100 bg-white' }} hover:shadow-xl transition">
                        @if($key === 'wedding')
                            <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full">Популярное</span>
                        @endif
                        <div class="text-4xl mt-2 mb-4">
                            @switch($key)
                                @case('banquet') 🍽️ @break
                                @case('buffet') 🥂 @break
                                @case('coffee_break') ☕ @break
                                @case('wedding') 💒 @break
                                @case('corporate') 💼 @break
                                @default 🎊
                            @endswitch
                        </div>
                        <h3 class="text-xl font-bold mb-2">{{ $type['label'] }}</h3>
                        <p class="text-4xl font-extrabold text-orange-600 mb-4">{{ number_format($type['price_per_person'], 0, ',', ' ') }} <span class="text-base font-normal text-gray-400">₽/чел</span></p>
                        <ul class="text-gray-500 space-y-2 mb-6">
                            <li>✓ Аренда зала</li>
                            <li>✓ Сервировка</li>
                            <li>✓ Обслуживание</li>
                            @if($key === 'wedding' || $key === 'banquet')
                                <li>✓ Живая музыка</li>
                            @endif
                            @if($key === 'coffee_break')
                                <li>✓ Чай/кофе безлимит</li>
                            @endif
                        </ul>
                        <a href="#contact" class="block text-center {{ $key === 'wedding' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700' }} font-bold px-4 py-3 rounded-xl hover:bg-orange-500 hover:text-white transition">Выбрать</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="contact" class="py-20 bg-gradient-to-br from-orange-500 to-pink-600">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-4xl font-bold text-white mb-4">Оставьте заявку</h2>
                <p class="text-white/80 text-lg">Заполните форму ниже, и мы свяжемся с вами для уточнения деталей</p>
            </div>

            @if (session('success'))
                <div class="bg-green-500/20 backdrop-blur border border-green-300/30 text-white px-6 py-4 rounded-2xl mb-6 text-center text-lg font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 shadow-2xl">
                <form action="{{ route('landing.store') }}" method="POST">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Ваше имя *</label>
                            <input type="text" name="client_name" value="{{ old('client_name') }}" required
                                class="w-full px-4 py-3 rounded-xl bg-white/20 backdrop-blur text-white placeholder-white/50 border border-white/20 focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                                placeholder="Иван Иванов">
                            @error('client_name') <p class="text-yellow-300 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Телефон *</label>
                            <input type="text" name="client_phone" value="{{ old('client_phone') }}" required
                                class="w-full px-4 py-3 rounded-xl bg-white/20 backdrop-blur text-white placeholder-white/50 border border-white/20 focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                                placeholder="+7 (999) 123-45-67">
                            @error('client_phone') <p class="text-yellow-300 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Email</label>
                            <input type="email" name="client_email" value="{{ old('client_email') }}"
                                class="w-full px-4 py-3 rounded-xl bg-white/20 backdrop-blur text-white placeholder-white/50 border border-white/20 focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                                placeholder="ivan@example.ru">
                        </div>
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Тип мероприятия *</label>
                            <select name="event_type" required
                                class="w-full px-4 py-3 rounded-xl bg-white/20 backdrop-blur text-white border border-white/20 focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                                @foreach($eventTypes as $key => $type)
                                    <option value="{{ $key }}" {{ old('event_type') == $key ? 'selected' : '' }} class="text-gray-900">{{ $type['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Дата мероприятия *</label>
                            <input type="date" name="event_date" value="{{ old('event_date') }}" required
                                class="w-full px-4 py-3 rounded-xl bg-white/20 backdrop-blur text-white border border-white/20 focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                            @error('event_date') <p class="text-yellow-300 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Время</label>
                            <input type="time" name="event_time" value="{{ old('event_time') }}"
                                class="w-full px-4 py-3 rounded-xl bg-white/20 backdrop-blur text-white border border-white/20 focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Количество гостей *</label>
                            <input type="number" name="people_count" value="{{ old('people_count', 10) }}" required min="1"
                                class="w-full px-4 py-3 rounded-xl bg-white/20 backdrop-blur text-white placeholder-white/50 border border-white/20 focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                            @error('people_count') <p class="text-yellow-300 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-white/80 text-sm font-medium mb-2">Пожелания</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-4 py-3 rounded-xl bg-white/20 backdrop-blur text-white placeholder-white/50 border border-white/20 focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                            placeholder="Опишите ваши пожелания...">{{ old('notes') }}</textarea>
                    </div>
                    <button type="submit" class="mt-6 w-full bg-yellow-400 text-orange-800 font-bold px-8 py-4 rounded-xl text-lg hover:bg-yellow-300 transform hover:scale-[1.02] transition-all shadow-xl">
                        Отправить заявку
                    </button>
                </form>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-white text-lg font-bold mb-2">☕ Кафе</p>
            <p>г. Москва, ул. Примерная, 123</p>
            <p>+7 (999) 123-45-67</p>
            <p class="mt-4 text-sm">© {{ date('Y') }} Кафе. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>
