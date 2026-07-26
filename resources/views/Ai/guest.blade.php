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
        <!-- START: AUTH NAVBAR -->
        <!-- ========================================== -->
        <nav class="bg-emerald-950 text-white border-b border-emerald-900 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 sm:h-20">
                    <a href="{{ route('front.index') }}"
                        class="flex items-center gap-2.5 font-bold text-lg sm:text-xl text-amber-400 hover:text-amber-300 transition">
                        <img class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover"
                            src="{{ asset('assets/img/icon.png') }}"
                            alt="شعار واحة الشهداء">
                        <span class="tracking-wide">واحة الشهداء</span>
                    </a>

                    <a href="{{ route('front.index') }}"
                        class="flex items-center gap-2 text-xs sm:text-sm text-gray-300 hover:text-amber-400 transition">
                        <i class="fa-solid fa-arrow-right"></i>
                        <span>العودة إلى الموقع</span>
                    </a>
                </div>
            </div>
        </nav>
        <!-- ========================================== -->
        <!-- END: AUTH NAVBAR -->
        <!-- ========================================== -->

        <!-- ========================================== -->
        <!-- START: AUTH CONTENT -->
        <!-- ========================================== -->
        <main class="relative flex-grow flex items-center justify-center overflow-hidden px-4 py-10 sm:py-14">
            <div class="absolute inset-0 bg-gradient-to-b from-emerald-950 via-emerald-950 to-emerald-900"></div>
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-amber-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>

            <div class="relative w-full max-w-md">
                <div class="text-center mb-7">
                    <div
                        class="w-20 h-20 mx-auto mb-4 rounded-full bg-white/10 border border-white/10 flex items-center justify-center shadow-lg">
                        <img class="w-16 h-16 rounded-full object-cover"
                            src="{{ asset('assets/img/icon.png') }}"
                            alt="شعار واحة الشهداء">
                    </div>

                    <span class="text-amber-400 text-xs font-bold tracking-widest">
                        بوابة الإدارة
                    </span>

                    <h1 class="mt-2 text-2xl sm:text-3xl font-bold text-white">
                        واحة الشهداء
                    </h1>

                    <p class="mt-2 text-sm text-gray-300">
                        الدخول المخصص لإدارة محتوى المنصة
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-2xl overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-emerald-700 via-amber-500 to-emerald-700"></div>

                    <div class="p-6 sm:p-8">
                        {{ $slot }}
                    </div>
                </div>

                <p class="mt-6 text-center text-xs text-gray-400">
                    جميع عمليات الدخول خاضعة للحماية والتوثيق
                </p>
            </div>
        </main>
        <!-- ========================================== -->
        <!-- END: AUTH CONTENT -->
        <!-- ========================================== -->

        <footer class="bg-emerald-950 border-t border-emerald-900 py-5">
            <div class="max-w-7xl mx-auto px-4 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} واحة الشهداء. جميع الحقوق محفوظة.
            </div>
        </footer>
    </div>
</body>
</html>
