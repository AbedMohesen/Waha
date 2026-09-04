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
    <body class="oasis-page antialiased">
        @include('layouts.navigation')

        @isset($header)
            <header class="border-b border-black/5 bg-white">
                <div class="oasis-container py-6 sm:py-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="pb-16">
            {{ $slot }}
        </main>

        <footer class="oasis-band border-t border-white/10 py-8 text-center text-xs text-white/70">
            <p>واحة الشهداء — مساحة تحفظ الذاكرة باحترام.</p>
        </footer>

        @isset($js)
            {{ $js }}
        @endisset
    </body>
</html>
