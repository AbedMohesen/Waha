<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link  rel="icon" href="{{ asset('assets/img/icon1.png') }}">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS v4 -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <title>@yield('title')</title>
</head>

<body class="bg-gray-50 text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- ========================================== -->
    <!-- START: NAVBAR (شريط التنقل العلوي) -->
    <!-- ========================================== -->
    <nav class="bg-emerald-950 text-white border-b border-emerald-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">

                <!-- Logo & Brand Name -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('front.index') }}" class="flex items-center gap-2.5 font-bold text-lg sm:text-xl text-amber-400 hover:text-amber-300 transition">
                        <img class="w-15 h-15 rounded-full" src="{{ asset('assets/img/icon.png') }}" alt="">
                        <span class="tracking-wide">واحة الشهداء</span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center gap-8 space-x-reverse text-xl font-medium">
                    <a href="{{ route('front.index') }}" class="text-amber-400 font-semibold py-2 border-b-2 border-amber-400">
                        الرئيسية
                    </a>
                    <a href="#about-section" class="text-gray-300 hover:text-amber-400 transition py-2 border-b-2 border-transparent hover:border-amber-400">
                        من نحن
                    </a>
                    <a href="#contact-section" class="text-gray-300 hover:text-amber-400 transition py-2 border-b-2 border-transparent hover:border-amber-400">
                        اتصل بنا
                    </a>
                </div>

                <!-- Fast Search Shortcut & Mobile Menu Toggle -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('front.search') }}" class="flex items-center gap-2 bg-amber-600 hover:bg-amber-500 text-white px-3.5 py-2 rounded-xl text-xs sm:text-sm font-medium transition shadow-sm">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>البحث</span>
                    </a>

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" type="button" aria-label="القائمة" class="md:hidden text-gray-300 hover:text-white p-2 rounded-lg focus:outline-none">
                        <i class="fa-solid fa-bars text-xl" id="menu-icon"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown -->
        <div id="mobile-menu" class="hidden md:hidden bg-emerald-900 border-t border-emerald-800 px-4 pt-3 pb-4 space-y-2 text-sm">
            <a href="{{ route('front.index') }}" class="block px-3 py-2 rounded-lg font-medium text-amber-400 bg-emerald-950">الرئيسية</a>
            <a href="#about-section" class="block px-3 py-2 rounded-lg font-medium text-gray-200 hover:text-amber-300 hover:bg-emerald-800 transition">من نحن</a>
            <a href="#contact-section" class="block px-3 py-2 rounded-lg font-medium text-gray-200 hover:text-amber-300 hover:bg-emerald-800 transition">اتصل بنا</a>
        </div>
    </nav>
    <!-- ========================================== -->
    <!-- END: NAVBAR -->
    <!-- ========================================== -->
@yield('content')

    <!-- ========================================== -->
    <!-- START: FOOTER (تذييل الصفحة) -->
    <!-- ========================================== -->
    <footer id="contact-section" class="bg-emerald-950 text-gray-300 border-t border-emerald-900 pt-12 pb-6 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">

                <!-- Footer Col 1: About Brief -->
                <div class="md:col-span-1 space-y-3">
                    <div class="flex items-center gap-2 text-amber-400 font-bold text-lg">
                        <i class="fa-solid fa-kaaba"></i>
                        <span>واحة الشهداء</span>
                    </div>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        سجل وطني وتوثيقي مخصص لحفظ أسماء الشهداء وبياناتهم بشكل منظم وسريع للوصول من قبل ذويهم والباحثين.
                    </p>
                </div>

                <!-- Footer Col 2: Quick Links -->
                <div>
                    <h4 class="text-sm font-semibold text-white mb-4 border-r-2 border-amber-400 pr-2">روابط سريعة</h4>
                    <ul class="space-y-2.5 text-xs text-gray-300">
                        <li><a href="{{ route('front.index') }}" class="hover:text-amber-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-left text-[10px]"></i> الرئيسية</a></li>
                        <li><a href="#about-section" class="hover:text-amber-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-left text-[10px]"></i> من نحن</a></li>
                        <li><a href="#search-section" class="hover:text-amber-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-left text-[10px]"></i> محرك البحث</a></li>
                    </ul>
                </div>

                <!-- Footer Col 3: Search Help -->
                <div>
                    <h4 class="text-sm font-semibold text-white mb-4 border-r-2 border-amber-400 pr-2">تنويه للبحث</h4>
                    <p class="text-xs text-gray-400 mb-3 leading-relaxed">
                        يمكنك إجراء البحث عن طريق كتابة الاسم الرباعي أو الثلاثي، أو إدخال رقم الهوية الوطنية المباشر.
                    </p>
                </div>

                <!-- Footer Col 4: Contact -->
                <div>
                    <h4 class="text-sm font-semibold text-white mb-4 border-r-2 border-amber-400 pr-2">تواصل معنا</h4>
                    <ul class="space-y-2.5 text-xs text-gray-400">
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-amber-400"></i>
                            <span>support@martyrs-oasis.com</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-amber-400"></i>
                            <span>جميع البيانات محفوظة وموثقة</span>
                        </li>
                    </ul>
                </div>

            </div>

            <hr class="border-emerald-900 my-6">

            <!-- Footer Bottom Rights -->
            <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-gray-400 gap-2">
                <p>&copy; {{ date('Y') }} واحة الشهداء. جميع الحقوق محفوظة.</p>
                <p class="text-gray-500">تم التطوير بكل إجلال ووفاء</p>
            </div>
        </div>
    </footer>
    <!-- ========================================== -->
    <!-- END: FOOTER -->
    <!-- ========================================== -->

    <!-- ========================================== -->
    <!-- START: JAVASCRIPT LOGIC -->
    <!-- ========================================== -->
    @yield('js')
    <!-- ========================================== -->
    <!-- END: JAVASCRIPT LOGIC -->
    <!-- ========================================== -->

</body>

</html>
