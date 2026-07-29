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

    <title>{{ $title ?? 'تسجيل الدخول | واحة الشهداء' }}</title>
</head>

<body class="bg-gray-50 text-slate-800 antialiased min-h-screen">
    <div class="min-h-screen flex flex-col">

        <!-- ========================================== -->
        <!-- START: AUTH CONTENT -->
        <!-- ========================================== -->
        <main class="relative flex-grow flex items-center justify-center overflow-hidden px-4 py-10 sm:py-14">
            <div class="absolute inset-0 bg-gradient-to-b from-emerald-950 via-emerald-950 to-emerald-900"></div>
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-amber-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>

            <div class="relative w-full max-w-md ">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-2xl overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-emerald-700 via-amber-500 to-emerald-700"></div>

                    <div class="p-6 sm:p-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>
        <!-- ========================================== -->
        <!-- END: AUTH CONTENT -->
        <!-- ========================================== -->
    </div>
</body>
</html>
