@extends('front.layout')
@section('title',' الرئيسية | واحة الشهداء')
@section('content')
    <!-- ========================================== -->
    <!-- START: HERO SECTION (قسم الواجهة الرئيسية) -->
    <!-- ========================================== -->
    <section class="relative bg-emerald-950 text-white py-16 sm:py-24 overflow-hidden">
        <!-- الخلفية الجمالية -->
        <div
            class="absolute inset-0  from-emerald-900/40 via-emerald-950 to-emerald-950">
        </div>
        <div class="absolute -top-16 -right-16 w-96 h-96 bg-amber-500/30 rounded-full blur-3xl"></div>

        <div class="relative max-w-5xl mx-auto px-4 text-center">
            <h1
                class="font-serif text-3xl sm:text-5xl font-bold tracking-wide text-white leading-tight sm:leading-snug mb-6">
                سجل الخلود والتكريم لشهداء الوطن
            </h1>

            <p class="text-gray-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed mb-8">
                نستذكر بطولاتهم، ونخلد أسماءهم وقصصهم العطرة لتبقى منارة يسترشد بها الأجيال.
            </p>

            <!-- زر الانتقال للبحث السريع -->
            <div class="flex flex-wrap justify-center items-center gap-4">
                <a href="{{ route('front.search') }}"
                    class="flex items-center gap-2.5 bg-amber-500 hover:bg-amber-400 text-emerald-950 font-bold px-6 py-3.5 rounded-xl text-sm sm:text-base transition shadow-lg hover:shadow-amber-500/20">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>البحث في قاعدة البيانات</span>
                </a>
            </div>
        </div>
    </section>
    <!-- ========================================== -->
    <!-- END: HERO SECTION -->
    <!-- ========================================== -->

    <!-- ========================================== -->
    <!-- START: FEATURED MARTYRS (أبرز الشهداء) -->
    <!-- ========================================== -->
    <section id="featured-martyrs" class="py-12 sm:py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 scroll-mt-20">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4 border-b border-gray-200 pb-4">
            <div>
                <span class="text-amber-600 font-bold text-xs tracking-widest uppercase flex items-center gap-1.5 mb-1">
                    <i class="fa-solid fa-award"></i> مختارات الأدمن
                </span>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-800">أبرز الشهداء</h2>
            </div>
            <a href="{{ route('front.search') }}"
                class="text-xs sm:text-sm text-emerald-700 hover:text-emerald-800 font-bold flex items-center gap-1 transition">
                <span>عرض كافّة السجلات</span>
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>

        <!-- شبكة بطاقات الشهداء -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($featuredMartyrs as $martyr)
                <article
                    class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition duration-200 group flex flex-col justify-between">
                    <div class="p-5 text-center">
                        <div
                            class="w-20 h-20 bg-emerald-50 border-2 border-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-800 group-hover:bg-emerald-700 group-hover:text-white transition">
                            <i class="fa-solid fa-user-shield text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-base text-slate-800 mb-1 group-hover:text-emerald-700 transition" dir="rtl">
                            {{ $martyr->name_ar }}
                        </h3>
                        @if ($martyr->name_en)
                            <p class="text-xs text-gray-500 mb-3">{{ $martyr->name_en }}</p>
                        @endif
                        <span
                            class="inline-block bg-amber-50 text-amber-700 border border-amber-200 text-[11px] px-2.5 py-1 rounded-full font-medium">
                            العمر: {{ $martyr->age ?: '-' }}
                        </span>
                    </div>
                    <a href="{{ route('martyr', $martyr->id) }}"
                        class="block bg-gray-50 border-t border-gray-100 text-center py-2.5 text-xs text-emerald-700 font-semibold hover:bg-emerald-50 transition">
                        عرض التفاصيل الكاملة &larr;
                    </a>
                </article>
            @empty
                <p class="col-span-full rounded-xl bg-gray-50 p-6 text-center text-sm text-gray-500">
                    لا توجد سجلات لعرضها حالياً.
                </p>
            @endforelse
        </div>

        <!-- التصميم التجريبي السابق محفوظ مؤقتاً للرجوع إليه عند الحاجة. -->
        <div class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- بطاقة تجريبية 1 -->
            <div
                class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition duration-200 group flex flex-col justify-between">
                <div class="p-5 text-center">
                    <div
                        class="w-20 h-20 bg-emerald-50 border-2 border-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-800 group-hover:bg-emerald-700 group-hover:text-white transition">
                        <i class="fa-solid fa-user-shield text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-base text-slate-800 mb-1 group-hover:text-emerald-700 transition">الشهيد
                        محمد أحمد علي</h3>
                    <p class="text-xs text-gray-500 mb-3">تاريخ الاستشهاد: 2023/10/12</p>
                    <span
                        class="inline-block bg-amber-50 text-amber-700 border border-amber-200 text-[11px] px-2.5 py-1 rounded-full font-medium">القدس
                        الشريف</span>
                </div>
                <a href="#"
                    class="block bg-gray-50 border-t border-gray-100 text-center py-2.5 text-xs text-emerald-700 font-semibold hover:bg-emerald-50 transition">
                    عرض التفاصيل الكاملة &larr;
                </a>
            </div>

            <!-- بطاقة تجريبية 2 -->
            <div
                class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition duration-200 group flex flex-col justify-between">
                <div class="p-5 text-center">
                    <div
                        class="w-20 h-20 bg-emerald-50 border-2 border-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-800 group-hover:bg-emerald-700 group-hover:text-white transition">
                        <i class="fa-solid fa-user-shield text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-base text-slate-800 mb-1 group-hover:text-emerald-700 transition">الشهيد
                        يوسف إبراهيم محمود</h3>
                    <p class="text-xs text-gray-500 mb-3">تاريخ الاستشهاد: 2024/01/05</p>
                    <span
                        class="inline-block bg-amber-50 text-amber-700 border border-amber-200 text-[11px] px-2.5 py-1 rounded-full font-medium">قطاع
                        غزة</span>
                </div>
                <a href="#"
                    class="block bg-gray-50 border-t border-gray-100 text-center py-2.5 text-xs text-emerald-700 font-semibold hover:bg-emerald-50 transition">
                    عرض التفاصيل الكاملة &larr;
                </a>
            </div>

            <!-- بطاقة تجريبية 3 -->
            <div
                class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition duration-200 group flex flex-col justify-between">
                <div class="p-5 text-center">
                    <div
                        class="w-20 h-20 bg-emerald-50 border-2 border-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-800 group-hover:bg-emerald-700 group-hover:text-white transition">
                        <i class="fa-solid fa-user-shield text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-base text-slate-800 mb-1 group-hover:text-emerald-700 transition">الشهيد
                        خالد عمر القاسم</h3>
                    <p class="text-xs text-gray-500 mb-3">تاريخ الاستشهاد: 2023/11/20</p>
                    <span
                        class="inline-block bg-amber-50 text-amber-700 border border-amber-200 text-[11px] px-2.5 py-1 rounded-full font-medium">جنين</span>
                </div>
                <a href="#"
                    class="block bg-gray-50 border-t border-gray-100 text-center py-2.5 text-xs text-emerald-700 font-semibold hover:bg-emerald-50 transition">
                    عرض التفاصيل الكاملة &larr;
                </a>
            </div>

            <!-- بطاقة تجريبية 4 -->
            <div
                class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition duration-200 group flex flex-col justify-between">
                <div class="p-5 text-center">
                    <div
                        class="w-20 h-20 bg-emerald-50 border-2 border-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-800 group-hover:bg-emerald-700 group-hover:text-white transition">
                        <i class="fa-solid fa-user-shield text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-base text-slate-800 mb-1 group-hover:text-emerald-700 transition">الشهيد
                        طارق حسن منصور</h3>
                    <p class="text-xs text-gray-500 mb-3">تاريخ الاستشهاد: 2024/02/14</p>
                    <span
                        class="inline-block bg-amber-50 text-amber-700 border border-amber-200 text-[11px] px-2.5 py-1 rounded-full font-medium">نابلس</span>
                </div>
                <a href="#"
                    class="block bg-gray-50 border-t border-gray-100 text-center py-2.5 text-xs text-emerald-700 font-semibold hover:bg-emerald-50 transition">
                    عرض التفاصيل الكاملة &larr;
                </a>
            </div>

        </div>
    </section>
    <!-- ========================================== -->
    <!-- END: FEATURED MARTYRS -->
    <!-- ========================================== -->

    <!-- ========================================== -->
    <!-- START: FEATURED STORIES (أبرز القصص) -->
    <!-- ========================================== -->
    <section id="featured-stories" class="py-12 bg-emerald-950/5 border-y border-emerald-900/10 scroll-mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <span class="text-amber-600 font-bold text-xs tracking-widest uppercase flex items-center gap-1.5 mb-1">
                    <i class="fa-solid fa-book-open"></i> سيرة وحكاية
                </span>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-800">أبرز القصص العطرة</h2>
            </div>

            <!-- شبكة القصص -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- قصة 1 -->
                <article
                    class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-xs text-amber-600 font-semibold mb-3">
                            <i class="fa-solid fa-bookmark"></i>
                            <span>قصة بطولة</span>
                        </div>
                        <h3 class="font-bold text-lg text-slate-800 mb-2 leading-snug">وصية الفجر الأخيرة</h3>
                        <p class="text-gray-600 text-xs sm:text-sm leading-relaxed mb-4 line-clamp-3">
                            كان يوصي الجميع بالحفاظ على الأرض والتراحم بين الناس، وترك خلفه إرثاً كبيراً من المحبة
                            والأعمال الصالحة التي يذكرها أهل بلدته حتى اليوم...
                        </p>
                    </div>
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span>عن الشهيد: أحمد المحمود</span>
                        <a href="#" class="text-emerald-700 font-bold hover:underline">قراءة القصة</a>
                    </div>
                </article>

                <!-- قصة 2 -->
                <article
                    class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-xs text-amber-600 font-semibold mb-3">
                            <i class="fa-solid fa-bookmark"></i>
                            <span>سيرة عطرة</span>
                        </div>
                        <h3 class="font-bold text-lg text-slate-800 mb-2 leading-snug">معلم الأجيال والسند</h3>
                        <p class="text-gray-600 text-xs sm:text-sm leading-relaxed mb-4 line-clamp-3">
                            قضى حياته في تعليم أجيال القرية معنى الوفاء والانتماء، وعُرف بابتسامته التي لم تفارقه حتى
                            لحظاته الأخيرة في ميدان العطاء...
                        </p>
                    </div>
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span>عن الشهيد: د. صلاح الدين</span>
                        <a href="#" class="text-emerald-700 font-bold hover:underline">قراءة القصة</a>
                    </div>
                </article>

                <!-- قصة 3 -->
                <article
                    class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-xs text-amber-600 font-semibold mb-3">
                            <i class="fa-solid fa-bookmark"></i>
                            <span>حكاية وفاء</span>
                        </div>
                        <h3 class="font-bold text-lg text-slate-800 mb-2 leading-snug">ابتسامة لا تغيب</h3>
                        <p class="text-gray-600 text-xs sm:text-sm leading-relaxed mb-4 line-clamp-3">
                            يروي رفاقه كيف كان يبعث الأمل في النفوس وقت الشدائد، وكيف ترك بصمة طيبة في كل بيت ومكان زاره
                            طوال مسيرة حياته...
                        </p>
                    </div>
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span>عن الشهيد: سامر الزيود</span>
                        <a href="#" class="text-emerald-700 font-bold hover:underline">قراءة القصة</a>
                    </div>
                </article>

            </div>
        </div>
    </section>
    <!-- ========================================== -->
    <!-- END: FEATURED STORIES -->
    <!-- ========================================== -->

    <!-- ========================================== -->
    <!-- START: FEATURED GALLERY (أبرز الصور) -->
    <!-- ========================================== -->
    <section id="featured-gallery" class="py-12 sm:py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 scroll-mt-20">
        <div class="mb-8 border-b border-gray-200 pb-4">
            <span class="text-amber-600 font-bold text-xs tracking-widest uppercase flex items-center gap-1.5 mb-1">
                <i class="fa-solid fa-images"></i> الأرشيف المصور
            </span>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-800">أبرز الصور والتوثيقات</h2>
        </div>

        <!-- معرض الصور (مكان مخصص لصور الأدمن) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <div class="relative group rounded-2xl overflow-hidden bg-gray-200 aspect-square shadow-sm">
                <div
                    class="w-full h-full bg-emerald-900/10 flex items-center justify-center text-emerald-800 group-hover:scale-105 transition duration-300">
                    <i class="fa-solid fa-image text-4xl text-gray-400"></i>
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition p-4 flex flex-col justify-end text-white text-xs">
                    <p class="font-bold">عنوان الصورة الموثقة</p>
                </div>
            </div>

            <div class="relative group rounded-2xl overflow-hidden bg-gray-200 aspect-square shadow-sm">
                <div
                    class="w-full h-full bg-emerald-900/10 flex items-center justify-center text-emerald-800 group-hover:scale-105 transition duration-300">
                    <i class="fa-solid fa-image text-4xl text-gray-400"></i>
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition p-4 flex flex-col justify-end text-white text-xs">
                    <p class="font-bold">عنوان الصورة الموثقة</p>
                </div>
            </div>

            <div class="relative group rounded-2xl overflow-hidden bg-gray-200 aspect-square shadow-sm">
                <div
                    class="w-full h-full bg-emerald-900/10 flex items-center justify-center text-emerald-800 group-hover:scale-105 transition duration-300">
                    <i class="fa-solid fa-image text-4xl text-gray-400"></i>
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition p-4 flex flex-col justify-end text-white text-xs">
                    <p class="font-bold">عنوان الصورة الموثقة</p>
                </div>
            </div>

            <div class="relative group rounded-2xl overflow-hidden bg-gray-200 aspect-square shadow-sm">
                <div
                    class="w-full h-full bg-emerald-900/10 flex items-center justify-center text-emerald-800 group-hover:scale-105 transition duration-300">
                    <i class="fa-solid fa-image text-4xl text-gray-400"></i>
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition p-4 flex flex-col justify-end text-white text-xs">
                    <p class="font-bold">عنوان الصورة الموثقة</p>
                </div>
            </div>

        </div>
    </section>
    <!-- ========================================== -->
    <!-- END: FEATURED GALLERY -->
    <!-- ========================================== -->
@endsection
