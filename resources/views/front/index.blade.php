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
                @php
                    $profilePath = $martyr->profileImg?->img_path;
                    $profileExists = filled($profilePath)
                        && \Illuminate\Support\Facades\Storage::disk('martyr_images')->exists($profilePath);
                    $profileUrl = $profileExists
                        ? asset('assets/img/' . ltrim(str_replace('\\', '/', $profilePath), '/'))
                        : asset('assets/img/No-photo-m.png');
                @endphp

                <article
                    class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition duration-200 group flex flex-col justify-between">
                    <div class="p-5 text-center">
                        <img
                            src="{{ $profileUrl }}"
                            alt="صورة الشهيد {{ $martyr->name_ar }}"
                            class="mx-auto mb-4 h-20 w-20 rounded-full border-2 border-emerald-100 bg-emerald-50 object-cover"
                        >
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
                    لم يتم اختيار شهداء مميزين حتى الآن.
                </p>
            @endforelse
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse ($featuredStories as $story)
                    <article
                        class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs text-amber-600 font-semibold mb-3">
                                <i class="fa-solid fa-bookmark"></i>
                                <span>سيرة عطرة</span>
                            </div>
                            <h3 class="font-bold text-lg text-slate-800 mb-2 leading-snug">{{ $story->title }}</h3>
                            <p class="text-gray-600 text-xs sm:text-sm leading-relaxed mb-4 line-clamp-3">
                                {{ \Illuminate\Support\Str::limit(strip_tags($story->content), 180) }}
                            </p>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-3 text-xs text-gray-500">
                            <span>عن الشهيد: {{ $story->martyr->name_ar }}</span>
                            <a href="{{ route('martyr', $story->martyr) }}" class="shrink-0 text-emerald-700 font-bold hover:underline">
                                قراءة القصة
                            </a>
                        </div>
                    </article>
                @empty
                    <p class="col-span-full rounded-xl bg-white p-6 text-center text-sm text-gray-500">
                        لم يتم اختيار قصص مميزة حتى الآن.
                    </p>
                @endforelse
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

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @forelse ($featuredMemoryImages as $memoryImage)
                @php
                    $memoryPath = $memoryImage->img_path;
                    $memoryExists = filled($memoryPath)
                        && \Illuminate\Support\Facades\Storage::disk('martyr_images')->exists($memoryPath);
                    $memoryUrl = $memoryExists
                        ? asset('assets/img/' . ltrim(str_replace('\\', '/', $memoryPath), '/'))
                        : asset('assets/img/No-photo-m.png');
                @endphp

                <a
                    href="{{ route('martyr', $memoryImage->martyr) }}"
                    class="relative group rounded-2xl overflow-hidden bg-gray-200 aspect-square shadow-sm"
                >
                    <img
                        src="{{ $memoryUrl }}"
                        alt="{{ $memoryImage->caption ?: 'صورة ذكرى للشهيد '.$memoryImage->martyr->name_ar }}"
                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                    >
                    <span class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black/75 via-transparent to-transparent p-4 text-xs text-white">
                        <strong>{{ $memoryImage->martyr->name_ar }}</strong>
                        @if (filled($memoryImage->caption))
                            <span class="mt-1 line-clamp-2">{{ $memoryImage->caption }}</span>
                        @endif
                    </span>
                </a>
            @empty
                <p class="col-span-full rounded-xl bg-gray-50 p-6 text-center text-sm text-gray-500">
                    لم يتم اختيار صور ذكريات مميزة حتى الآن.
                </p>
            @endforelse
        </div>
    </section>
    <!-- ========================================== -->
    <!-- END: FEATURED GALLERY -->
    <!-- ========================================== -->
@endsection
