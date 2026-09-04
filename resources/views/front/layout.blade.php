<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1E3932">
    <link rel="icon" href="{{ asset('assets/img/icon1.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>@yield('title', 'واحة الشهداء')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="oasis-page flex min-h-screen flex-col antialiased">
    <nav x-data="{ open: false }" class="oasis-nav sticky top-0 z-50 bg-white" aria-label="التنقل الرئيسي">
        <div class="oasis-container flex min-h-16 items-center justify-between gap-4 py-3 sm:min-h-[72px] lg:min-h-[83px]">
            <a href="{{ route('front.index') }}" class="flex items-center gap-3 text-oasis-green">
                <img class="h-11 w-11 rounded-full border border-oasis-mint bg-white p-1" src="{{ asset('assets/img/icon.png') }}" alt="شعار واحة الشهداء">
                <span class="text-sm font-extrabold sm:text-base">واحة الشهداء</span>
            </a>

            <div class="hidden items-center gap-6 lg:flex">
                <a href="{{ route('front.index') }}" @class(['oasis-nav-link', 'oasis-nav-link-active' => request()->routeIs('front.index')])>الرئيسية</a>
                <a href="{{ route('front.search') }}" @class(['oasis-nav-link', 'oasis-nav-link-active' => request()->routeIs('front.search') || request()->routeIs('martyr')])>السجل</a>
                <a href="{{ route('front.about') }}" @class(['oasis-nav-link', 'oasis-nav-link-active' => request()->routeIs('front.about')])>عن الواحة</a>
                <a href="{{ route('front.contact') }}" @class(['oasis-nav-link', 'oasis-nav-link-active' => request()->routeIs('front.contact')])>تواصل</a>
            </div>

            <div class="hidden items-center gap-2 lg:flex">
                @auth
                    <a href="{{ route('dashboard.index') }}" class="oasis-button oasis-button-outline text-xs">لوحة الإدارة</a>
                @else
                    <a href="{{ route('login') }}" class="oasis-button oasis-button-outline text-xs">تسجيل الدخول</a>
                @endauth
                <a href="{{ route('front.search') }}" class="oasis-button oasis-button-dark text-xs">ابحث في السجل</a>
            </div>

            <button type="button" class="grid h-11 w-11 place-items-center rounded-full border border-black/15 text-oasis-house lg:hidden" x-on:click="open = ! open" x-bind:aria-expanded="open.toString()" aria-label="فتح القائمة">
                <i class="fa-solid" x-bind:class="open ? 'fa-xmark' : 'fa-bars'"></i>
            </button>
        </div>

        <div x-show="open" x-transition class="border-t border-black/5 bg-oasis-cream p-4 lg:hidden">
            <div class="oasis-container grid gap-1 px-0">
                <a href="{{ route('front.index') }}" class="rounded-lg px-4 py-3 text-sm font-semibold hover:bg-white">الرئيسية</a>
                <a href="{{ route('front.search') }}" class="rounded-lg px-4 py-3 text-sm font-semibold hover:bg-white">السجل</a>
                <a href="{{ route('front.about') }}" class="rounded-lg px-4 py-3 text-sm font-semibold hover:bg-white">عن الواحة</a>
                <a href="{{ route('front.contact') }}" class="rounded-lg px-4 py-3 text-sm font-semibold hover:bg-white">تواصل معنا</a>
                <div class="mt-2 border-t border-black/5 pt-3"><a href="{{ route('front.search') }}" class="oasis-button oasis-button-primary w-full">ابحث في السجل</a></div>
            </div>
        </div>
    </nav>

    <main class="flex-1">
        @yield('content')
    </main>

    <a href="{{ route('front.search') }}" class="oasis-fab" aria-label="البحث في السجل" title="البحث في السجل"><i class="fa-solid fa-magnifying-glass"></i></a>

    <footer class="oasis-band mt-12 border-t border-white/10">
        <div class="oasis-container grid gap-8 py-10 text-sm sm:grid-cols-2 lg:grid-cols-4">
            <section>
                <div class="flex items-center gap-2 font-extrabold text-white"><img class="h-8 w-8 rounded-full bg-white p-0.5" src="{{ asset('assets/img/icon.png') }}" alt=""><span>واحة الشهداء</span></div>
                <p class="mt-4 max-w-xs leading-7 text-white/70">منصة توثيقية تحفظ الأسماء والقصص والذكريات ضمن تجربة إنسانية هادئة وواضحة.</p>
            </section>
            <section>
                <h2 class="font-bold text-white">استكشف</h2>
                <div class="mt-4 grid gap-3 text-white/70"><a class="hover:text-white" href="{{ route('front.index') }}">الرئيسية</a><a class="hover:text-white" href="{{ route('front.search') }}">البحث في السجل</a><a class="hover:text-white" href="{{ route('front.about') }}">عن الواحة</a></div>
            </section>
            <section>
                <h2 class="font-bold text-white">نساعدك على البحث</h2>
                <p class="mt-4 leading-7 text-white/70">ابحث بالاسم العربي أو الرقم الوطني للوصول إلى السجل المطلوب.</p>
            </section>
            <section>
                <h2 class="font-bold text-white">تواصل</h2>
                <a class="mt-4 inline-flex items-center gap-2 text-white/70 hover:text-white" href="mailto:support@martyrs-oasis.com"><i class="fa-solid fa-envelope"></i><span dir="ltr">support@martyrs-oasis.com</span></a>
            </section>
        </div>
        <div class="border-t border-white/10"><div class="oasis-container flex flex-col gap-2 py-5 text-xs text-white/60 sm:flex-row sm:items-center sm:justify-between"><span>© {{ date('Y') }} واحة الشهداء</span><span>ذاكرة موثقة، بتجربة تحترم الإنسان.</span></div></div>
    </footer>

    @yield('js')
</body>
</html>
