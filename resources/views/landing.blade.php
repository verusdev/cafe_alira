<!DOCTYPE html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кафе — банкеты и мероприятия</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .fade-up { opacity: 0; transform: translateY(30px); transition: all 0.7s ease-out; }
        .fade-up.show { opacity: 1; transform: translateY(0); }
        .shimmer { background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent); background-size: 200% 100%; animation: shimmer 3s infinite; }
        @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    </style>
</head>
<body class="font-sans antialiased bg-stone-50 text-stone-800">
    <!-- Nav -->
    <nav class="fixed top-0 inset-x-0 z-50 bg-white/70 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="#" class="flex items-center gap-2">
                <span class="w-8 h-8 bg-amber-600 rounded-lg flex items-center justify-center text-white text-sm font-bold">K</span>
                <span class="font-display text-xl font-bold text-stone-800">Кафе</span>
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="#services" class="text-stone-500 hover:text-amber-600 transition text-sm font-medium">Услуги</a>
                <a href="#pricing" class="text-stone-500 hover:text-amber-600 transition text-sm font-medium">Цены</a>
                <a href="#reviews" class="text-stone-500 hover:text-amber-600 transition text-sm font-medium">Отзывы</a>
                <a href="#contact" class="text-stone-500 hover:text-amber-600 transition text-sm font-medium">Контакты</a>
                <a href="{{ route('login') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2 rounded-full text-sm font-semibold transition shadow-lg shadow-amber-600/20">Войти</a>
            </div>
            <button class="md:hidden p-2 text-stone-600" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
        <div id="mobile-menu" class="hidden md:hidden px-4 pb-4">
            <div class="flex flex-col gap-3 bg-white rounded-2xl p-4 shadow-xl">
                <a href="#services" class="text-stone-600 py-2" onclick="document.getElementById('mobile-menu').classList.add('hidden')">Услуги</a>
                <a href="#pricing" class="text-stone-600 py-2" onclick="document.getElementById('mobile-menu').classList.add('hidden')">Цены</a>
                <a href="#reviews" class="text-stone-600 py-2" onclick="document.getElementById('mobile-menu').classList.add('hidden')">Отзывы</a>
                <a href="#contact" class="text-stone-600 py-2" onclick="document.getElementById('mobile-menu').classList.add('hidden')">Контакты</a>
                <a href="{{ route('login') }}" class="bg-amber-600 text-white text-center px-4 py-2.5 rounded-xl font-semibold">Войти</a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="relative min-h-screen flex items-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-800 via-stone-800 to-stone-900">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 25% 25%, white 1px, transparent 1px), radial-gradient(circle at 75% 75%, white 1px, transparent 1px); background-size: 60px 60px;"></div>
            <div class="absolute top-20 -left-20 w-96 h-96 bg-amber-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 -right-20 w-80 h-80 bg-orange-500/20 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-stone-50 via-transparent to-transparent"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 pt-24 pb-32 md:pb-48">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-4 py-1.5 mb-6">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-white/80 text-sm font-medium">Принимаем заказы на мероприятия</span>
                </div>
                <h1 class="font-display text-5xl md:text-7xl lg:text-8xl font-bold text-white leading-[1.1] mb-6">
                    Ваше идеальное<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-orange-400 italic">мероприятие</span>
                </h1>
                <p class="text-lg md:text-xl text-stone-200 max-w-xl mb-10 leading-relaxed">
                    Организуйте банкет, свадьбу или корпоратив в уютной атмосфере. Вкусная кухня, профессиональный сервис и индивидуальный подход к каждому событию.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#contact" class="bg-amber-600 hover:bg-amber-700 text-white font-semibold px-8 py-3.5 rounded-full shadow-2xl shadow-amber-600/30 hover:shadow-amber-600/40 transition-all hover:-translate-y-0.5 text-base">
                        Оставить заявку
                    </a>
                    <a href="#services" class="bg-white/10 backdrop-blur-md hover:bg-white/20 text-white border border-white/20 px-8 py-3.5 rounded-full transition text-base font-medium">
                        Наши услуги
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 inset-x-0 h-32 bg-gradient-to-t from-stone-50 to-transparent pointer-events-none"></div>
    </section>

    <!-- Stats -->
    <section class="relative -mt-20 z-10 max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl p-6 shadow-xl shadow-stone-900/5 border border-white/50 text-center">
                <p class="text-3xl md:text-4xl font-bold text-amber-600">12+</p>
                <p class="text-stone-500 text-sm mt-1">Лет опыта</p>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl p-6 shadow-xl shadow-stone-900/5 border border-white/50 text-center">
                <p class="text-3xl md:text-4xl font-bold text-amber-600">500+</p>
                <p class="text-stone-500 text-sm mt-1">Мероприятий</p>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl p-6 shadow-xl shadow-stone-900/5 border border-white/50 text-center">
                <p class="text-3xl md:text-4xl font-bold text-amber-600">50+</p>
                <p class="text-stone-500 text-sm mt-1">Блюд в меню</p>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl p-6 shadow-xl shadow-stone-900/5 border border-white/50 text-center">
                <p class="text-3xl md:text-4xl font-bold text-amber-600">98%</p>
                <p class="text-stone-500 text-sm mt-1">Довольных клиентов</p>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section id="services" class="py-24 md:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-16 fade-up">
                <span class="text-amber-600 font-semibold text-sm uppercase tracking-widest">Услуги</span>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-stone-800 mt-3 mb-4">Всё для вашего праздника</h2>
                <p class="text-stone-500">От банкета до кофе-брейка — мы сделаем ваше мероприятие незабываемым</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="group bg-white rounded-3xl p-8 shadow-lg shadow-stone-900/5 hover:shadow-xl hover:shadow-amber-600/5 transition-all duration-500 border border-stone-100 hover:border-amber-100 fade-up">
                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-amber-100 transition-colors">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-display text-xl font-bold text-stone-800 mb-2">Банкетное меню</h3>
                    <p class="text-stone-500 leading-relaxed">Разнообразное меню от шеф-повара. Индивидуальный подбор блюд под любой формат и бюджет мероприятия.</p>
                </div>
                <div class="group bg-white rounded-3xl p-8 shadow-lg shadow-stone-900/5 hover:shadow-xl hover:shadow-amber-600/5 transition-all duration-500 border border-stone-100 hover:border-amber-100 fade-up">
                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-amber-100 transition-colors">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-display text-xl font-bold text-stone-800 mb-2">Организация праздников</h3>
                    <p class="text-stone-500 leading-relaxed">Дни рождения, юбилеи, свадьбы, корпоративы — поможем с организацией мероприятия любого масштаба.</p>
                </div>
                <div class="group bg-white rounded-3xl p-8 shadow-lg shadow-stone-900/5 hover:shadow-xl hover:shadow-amber-600/5 transition-all duration-500 border border-stone-100 hover:border-amber-100 fade-up">
                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-amber-100 transition-colors">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                    <h3 class="font-display text-xl font-bold text-stone-800 mb-2">Кейтеринг и доставка</h3>
                    <p class="text-stone-500 leading-relaxed">Доставка готовых блюд к вашему столу. Свежая кухня, удобная упаковка, быстрая подача в любую точку города.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery / Atmosphere -->
    <section class="py-16 md:py-24 bg-stone-100/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="grid md:grid-cols-2 gap-6 items-center fade-up">
                <div>
                    <span class="text-amber-600 font-semibold text-sm uppercase tracking-widest">Атмосфера</span>
                    <h2 class="font-display text-4xl md:text-5xl font-bold text-stone-800 mt-3 mb-4">Уют и элегантность<br>в каждой детали</h2>
                    <p class="text-stone-500 leading-relaxed mb-6">Наше кафе — это пространство, где каждая деталь продумана для вашего комфорта. Стильный интерьер, мягкий свет и внимательный персонал создают идеальную атмосферу для любого торжества.</p>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-stone-600 font-medium">Зал до 100 гостей</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-stone-600 font-medium">Авторская кухня</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-stone-600 font-medium">VIP-обслуживание</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl w-full h-48 md:h-64 shadow-lg bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    </div>
                    <div class="rounded-2xl w-full h-48 md:h-64 object-cover shadow-lg mt-8 bg-gradient-to-br from-amber-600 to-stone-700 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A2.701 2.701 0 003 15.546M21 15.546V21H3v-5.454M21 15.546l-2.25-7.5-2.25 3.75M12 15.546l2.25-7.5L12 5.046l-2.25 3"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section id="pricing" class="py-24 md:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-16 fade-up">
                <span class="text-amber-600 font-semibold text-sm uppercase tracking-widest">Цены</span>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-stone-800 mt-3 mb-4">Выберите формат</h2>
                <p class="text-stone-500">Прозрачные цены без скрытых платежей. Базовая стоимость от 800 до 3 000 ₽ за человека</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($eventTypes as $key => $type)
                    <div class="group relative bg-white rounded-3xl p-8 shadow-lg shadow-stone-900/5 border-2 transition-all duration-500 hover:shadow-xl {{ $key === 'wedding' ? 'border-amber-400 scale-[1.02] md:scale-105' : 'border-stone-100 hover:border-amber-200' }} fade-up">
                        @if($key === 'wedding')
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg">
                                Популярное
                            </div>
                        @endif
                        <div class="w-12 h-12 {{ $key === 'wedding' ? 'bg-amber-100' : 'bg-stone-100' }} rounded-xl flex items-center justify-center mb-4">
                            @switch($key)
                                @case('banquet') <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg> @break
                                @case('buffet') <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> @break
                                @case('coffee_break') <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zm4-4h8l1 4H5l1-4z"/></svg> @break
                                @case('wedding') <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg> @break
                                @case('corporate') <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg> @break
                                @default <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg> @break
                            @endswitch
                        </div>
                        <h3 class="font-display text-xl font-bold text-stone-800 mb-1">{{ $type['label'] }}</h3>
                        <p class="text-4xl font-bold text-amber-600 mb-6">{{ number_format($type['price_per_person'], 0, ',', ' ') }} <span class="text-sm font-normal text-stone-400">₽/чел</span></p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-start gap-2 text-stone-500 text-sm"><svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Аренда зала</li>
                            <li class="flex items-start gap-2 text-stone-500 text-sm"><svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Сервировка стола</li>
                            <li class="flex items-start gap-2 text-stone-500 text-sm"><svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Обслуживание официантов</li>
                            @if($key === 'wedding' || $key === 'banquet')
                                <li class="flex items-start gap-2 text-stone-500 text-sm"><svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Живая музыка</li>
                            @endif
                            @if($key === 'coffee_break')
                                <li class="flex items-start gap-2 text-stone-500 text-sm"><svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Чай/кофе безлимит</li>
                            @endif
                        </ul>
                        <a href="#contact" data-event-type="{{ $key }}" class="select-event block text-center {{ $key === 'wedding' ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/20 hover:bg-amber-700' : 'bg-stone-100 text-stone-700 hover:bg-amber-600 hover:text-white' }} font-semibold px-4 py-3 rounded-xl transition-all">
                            Выбрать
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Reviews -->
    <section id="reviews" class="py-16 md:py-24 bg-stone-100/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-12 fade-up">
                <span class="text-amber-600 font-semibold text-sm uppercase tracking-widest">Отзывы</span>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-stone-800 mt-3 mb-4">Нас рекомендуют</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white rounded-3xl p-8 shadow-lg shadow-stone-900/5 border border-stone-100 fade-up">
                    <div class="flex gap-1 mb-4">
                        @for($i=0;$i<5;$i++) <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> @endfor
                    </div>
                    <p class="text-stone-600 leading-relaxed mb-4">«Заказывали свадебный банкет. Всё прошло идеально — красивая сервировка, вкуснейшие блюда, внимательный персонал. Спасибо большое!»</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-700 font-bold">АН</div>
                        <div><p class="font-semibold text-stone-800 text-sm">Анна Н.</p><p class="text-stone-400 text-xs">Свадьба</p></div>
                    </div>
                </div>
                <div class="bg-white rounded-3xl p-8 shadow-lg shadow-stone-900/5 border border-stone-100 fade-up">
                    <div class="flex gap-1 mb-4">
                        @for($i=0;$i<5;$i++) <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> @endfor
                    </div>
                    <p class="text-stone-600 leading-relaxed mb-4">«Провели корпоратив на 50 человек. Отличная организация, вкусное меню, все гости остались довольны. Обязательно вернёмся!»</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-700 font-bold">МК</div>
                        <div><p class="font-semibold text-stone-800 text-sm">Михаил К.</p><p class="text-stone-400 text-xs">Корпоратив</p></div>
                    </div>
                </div>
                <div class="bg-white rounded-3xl p-8 shadow-lg shadow-stone-900/5 border border-stone-100 fade-up">
                    <div class="flex gap-1 mb-4">
                        @for($i=0;$i<5;$i++) <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> @endfor
                    </div>
                    <p class="text-stone-600 leading-relaxed mb-4">«Заказывали кофе-брейк для деловой встречи. Всё привезли вовремя, свежая выпечка, отличный кофе. Сервис на высшем уровне!»</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-700 font-bold">ЕВ</div>
                        <div><p class="font-semibold text-stone-800 text-sm">Елена В.</p><p class="text-stone-400 text-xs">Кофе-брейк</p></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact" class="py-24 md:py-32 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-700 via-stone-800 to-stone-900">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 50% 50%, white 0.5px, transparent 0.5px); background-size: 30px 30px;"></div>
            <div class="absolute top-10 left-1/3 w-64 h-64 bg-amber-500/15 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-1/4 w-96 h-96 bg-orange-500/15 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12 fade-up">
                <span class="text-amber-300 font-semibold text-sm uppercase tracking-widest">Свяжитесь с нами</span>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-white mt-3 mb-4">Оставьте заявку</h2>
                <p class="text-amber-100/80 text-lg">Заполните форму, и мы свяжемся с вами для уточнения деталей</p>
            </div>

            @if (session('success'))
                <div class="bg-green-500/20 backdrop-blur border border-green-300/30 text-white px-6 py-4 rounded-2xl mb-6 text-center text-lg font-medium fade-up show">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 md:p-10 shadow-2xl border border-white/10 fade-up">
                <form action="{{ route('landing.store') }}" method="POST">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-amber-100 text-sm font-medium mb-1.5">Ваше имя *</label>
                            <input type="text" name="client_name" value="{{ old('client_name') }}" required
                                class="w-full px-4 py-3.5 rounded-xl bg-white/10 backdrop-blur text-white placeholder-amber-200/50 border border-white/20 focus:ring-2 focus:ring-amber-400 focus:outline-none transition"
                                placeholder="Иван Иванов">
                            @error('client_name') <p class="text-amber-300 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-amber-100 text-sm font-medium mb-1.5">Телефон *</label>
                            <input type="tel" name="client_phone" value="{{ old('client_phone') }}" required
                                class="w-full px-4 py-3.5 rounded-xl bg-white/10 backdrop-blur text-white placeholder-amber-200/50 border border-white/20 focus:ring-2 focus:ring-amber-400 focus:outline-none transition"
                                placeholder="+7 (999) 123-45-67">
                            @error('client_phone') <p class="text-amber-300 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-amber-100 text-sm font-medium mb-1.5">Email</label>
                            <input type="email" name="client_email" value="{{ old('client_email') }}"
                                class="w-full px-4 py-3.5 rounded-xl bg-white/10 backdrop-blur text-white placeholder-amber-200/50 border border-white/20 focus:ring-2 focus:ring-amber-400 focus:outline-none transition"
                                placeholder="ivan@example.ru">
                        </div>
                        <div>
                            <label class="block text-amber-100 text-sm font-medium mb-1.5">Тип мероприятия *</label>
                            <select id="event_type" name="event_type" required
                                class="w-full px-4 py-3.5 rounded-xl bg-white/10 backdrop-blur text-white border border-white/20 focus:ring-2 focus:ring-amber-400 focus:outline-none transition">
                                @foreach($eventTypes as $key => $type)
                                    <option value="{{ $key }}" {{ old('event_type') == $key ? 'selected' : '' }} class="bg-stone-800 text-white">{{ $type['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-amber-100 text-sm font-medium mb-1.5">Дата *</label>
                            <input type="date" name="event_date" value="{{ old('event_date') }}" required
                                class="w-full px-4 py-3.5 rounded-xl bg-white/10 backdrop-blur text-white border border-white/20 focus:ring-2 focus:ring-amber-400 focus:outline-none transition [color-scheme:dark]">
                            @error('event_date') <p class="text-amber-300 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-amber-100 text-sm font-medium mb-1.5">Время</label>
                            <input type="time" name="event_time" value="{{ old('event_time') }}"
                                class="w-full px-4 py-3.5 rounded-xl bg-white/10 backdrop-blur text-white border border-white/20 focus:ring-2 focus:ring-amber-400 focus:outline-none transition [color-scheme:dark]">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-amber-100 text-sm font-medium mb-1.5">Количество гостей *</label>
                            <input type="number" name="people_count" value="{{ old('people_count', 10) }}" required min="1"
                                class="w-full px-4 py-3.5 rounded-xl bg-white/10 backdrop-blur text-white placeholder-amber-200/50 border border-white/20 focus:ring-2 focus:ring-amber-400 focus:outline-none transition">
                            @error('people_count') <p class="text-amber-300 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-amber-100 text-sm font-medium mb-1.5">Пожелания</label>
                            <textarea name="notes" rows="3"
                                class="w-full px-4 py-3.5 rounded-xl bg-white/10 backdrop-blur text-white placeholder-amber-200/50 border border-white/20 focus:ring-2 focus:ring-amber-400 focus:outline-none transition"
                                placeholder="Опишите ваши пожелания...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="mt-6 w-full bg-amber-500 hover:bg-amber-400 text-stone-900 font-bold px-8 py-4 rounded-xl text-lg transition-all hover:-translate-y-0.5 shadow-xl shadow-amber-600/20">
                        Отправить заявку
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-stone-900 text-stone-400 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-8 h-8 bg-amber-600 rounded-lg flex items-center justify-center text-white text-sm font-bold">K</span>
                        <span class="font-display text-xl font-bold text-white">Кафе</span>
                    </div>
                    <p class="text-sm leading-relaxed">Уютное кафе для проведения банкетов, свадеб, корпоративов и других мероприятий.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Услуги</h4>
                    <ul class="space-y-2 text-sm">
                        <li>Банкетное меню</li>
                        <li>Организация праздников</li>
                        <li>Кейтеринг</li>
                        <li>Доставка блюд</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Контакты</h4>
                    <ul class="space-y-2 text-sm">
                        <li>г. Москва, ул. Примерная, 123</li>
                        <li>+7 (999) 123-45-67</li>
                        <li>info@cafe.ru</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Часы работы</h4>
                    <ul class="space-y-2 text-sm">
                        <li>Пн-Пт: 10:00 - 23:00</li>
                        <li>Сб-Вс: 11:00 - 02:00</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-stone-800 mt-10 pt-8 text-center text-sm">
                <p>© {{ date('Y') }} Кафе. Все права защищены.</p>
            </div>
        </div>
    </footer>

    <script>
        // Scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

        // Pricing select
        document.querySelectorAll('.select-event').forEach(function(link) {
            link.addEventListener('click', function(e) {
                var type = this.getAttribute('data-event-type');
                var select = document.getElementById('event_type');
                if (select) { select.value = type; }
            });
        });
    </script>
</body>
</html>
