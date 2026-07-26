<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('assets/img/icon1.png') }}">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Application Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>{{ $title ?? 'لوحة التحكم | واحة الشهداء' }}</title>

    <style>[x-cloak] { display: none !important; }</style>
</head>

<body class="bg-gray-50 text-slate-800 antialiased min-h-screen">
    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Dashboard Footer -->
        <footer class="bg-emerald-950 text-gray-400 border-t border-emerald-900 py-5 mt-12">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs">
                <p>&copy; {{ date('Y') }} واحة الشهداء. جميع الحقوق محفوظة.</p>
                <a href="{{ route('front.index') }}"
                    class="hover:text-amber-400 transition">
                    الانتقال إلى الموقع العام
                </a>
            </div>
        </footer>
    </div>

    {{ $js ?? '' }}
</body>
</html>
