<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#1E3932">
        <link rel="icon" href="{{ asset('assets/img/icon1.png') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <title>{{ config('app.name', 'واحة الشهداء') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="oasis-page grid min-h-screen place-items-center p-4 sm:p-6">
        <main class="w-full max-w-xl">
            <a href="{{ route('front.index') }}" class="mb-6 inline-flex items-center gap-3 text-sm font-bold text-oasis-green">
                <img class="h-11 w-11 rounded-full border border-oasis-mint bg-white p-1" src="{{ asset('assets/img/icon.png') }}" alt="">
                <span>واحة الشهداء</span>
            </a>
            <section class="oasis-card p-6 sm:p-8">
                {{ $slot }}
            </section>
        </main>
    </body>
</html>
